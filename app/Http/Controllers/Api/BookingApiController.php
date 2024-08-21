<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\BookingLog;

class BookingApiController extends Controller
{
    private function logBookingAction(Booking $booking, string $action, array $changes = []): void
    {
        BookingLog::create([
            'booking_id' => $booking->id,
            'action' => $action,
            'changes' => $changes,
            'user_id' => auth()->id(),
        ]);
    }
    public function index(Request $request)
    {
        $query = Booking::query();

        if ($request->filled('passenger_name')) {
            $query->where('passenger_name', 'like', '%' . $request->passenger_name . '%');
        }

        if ($request->filled('passenger_email')) {
            $query->where('passenger_email', 'like', '%' . $request->passenger_email . '%');
        }

        if ($request->filled('flight_id')) {
            $query->where('flight_id', $request->flight_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->get();
        return BookingResource::collection($bookings);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'flight_id' => 'required|exists:flights,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email',
            'credit_card_number' => 'required|string|max:19',
            'credit_card_expiry_date' => 'required|string|size:5',
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

        // Update available seats
        $flight->available_seats -= $request->number_seats;
        $flight->save();

        // Log the booking creation
        $this->logBookingAction($booking, 'created', $booking->toArray());

        return new BookingResource($booking);
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        return new BookingResource($booking);
    }


    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'passenger_name' => 'string|max:255',
            'passenger_email' => 'email',
            'number_seats' => 'integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $flight = Flight::findOrFail($booking->flight_id);

            // Calculate new available seats
            $seatsDifference = $request->number_seats - $booking->number_seats;
            $newAvailableSeats = $flight->available_seats - $seatsDifference;

            if ($newAvailableSeats < 0) {
                throw new \Exception('Not enough available seats');
            }

            // Update flight's available seats
            $flight->available_seats = $newAvailableSeats;
            $flight->save();

            // Calculate total cost
            $totalCost = $request->number_seats * $flight->price;

            // Update booking
            $booking->update([
                'passenger_name' => $request->passenger_name,
                'passenger_email' => $request->passenger_email,
                'number_seats' => $request->number_seats,
                'total_cost' => $totalCost,
            ]);

            DB::commit();

            // Log the booking update
            $this->logBookingAction($booking, 'updated', $booking->getChanges());

            return new BookingResource($booking);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Log the booking deletion before actually deleting
        $this->logBookingAction($booking, 'deleted', $booking->toArray());

        $booking->delete();

        return response()->json([
            'message' => 'Booking deleted successfully'
        ]);
    }
}
