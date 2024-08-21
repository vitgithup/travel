<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightApiController extends Controller
{

    public function index(Request $request)
    {
        $query = Flight::query();

        if ($request->filled('departure_airport')) {
            $query->where('departure_airport', $request->departure_airport);
        }

        if ($request->filled('arrival_airport')) {
            $query->where('arrival_airport', $request->arrival_airport);
        }

        if ($request->filled('departure_date')) {
            $query->whereDate('departure_time', $request->departure_date);
        }

        // Always check for available seats
        $query->where('available_seats', '>', 0);

        // Execute the query only if at least one parameter is provided
        if ($request->anyFilled(['departure_airport', 'arrival_airport', 'departure_date'])) {
            $flights = $query->get();
        } else {
            $flights = collect(); // Return an empty collection if no parameters are provided
        }

        return response()->json($flights);
    }



    private function logBookingAction(Booking $booking, string $action, array $changes = []): void
    {
        BookingLog::create([
            'booking_id' => $booking->id,
            'action' => $action,
            'changes' => $changes,
            'user_id' => auth()->id(),
        ]);
    }


    public function booking(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'flight_id' => 'required|exists:flights,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email',
            'credit_card_number' => 'required|string|max:19',
            'credit_card_expiry_date' => 'required|string|size:4',
            'credit_card_cvv' => 'required|string|size:3',
            'number_seats' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the flight is available
        $flight = Flight::find($request->flight_id);
        if (!$flight || $flight->available_seats < $request->number_seats) {
            return response()->json(['message' => 'Not enough seats available'], 400);
        }

        // Calculate total cost
        $total_cost = $flight->price * $request->number_seats;

        // Create the booking
        $booking = new Booking();
        $booking->flight_id = $request->flight_id;
        $booking->passenger_name = $request->passenger_name;
        $booking->passenger_email = $request->passenger_email;
        $booking->credit_card_number = encrypt($request->credit_card_number);
        $booking->credit_card_expiry_date = encrypt($request->credit_card_expiry_date);
        $booking->credit_card_cvv = encrypt($request->credit_card_cvv);
        $booking->number_seats = $request->number_seats;
        $booking->total_cost = $total_cost;
        $booking->save();

        $this->logBookingAction($booking, 'created', $booking->toArray());
        // Update available seats
        $flight->available_seats -= $request->number_seats;
        $flight->save();

        return response()->json([
            'message' => 'Booking successful',
            'booking_id' => $booking->id,
            'total_cost' => $total_cost
        ], 201);
    }
}
