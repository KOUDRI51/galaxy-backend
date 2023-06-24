<?php

namespace App\Http\Controllers;

use App\Models\Investment;
use App\Models\User;
use Illuminate\Http\Request;
use Property;

class InvestmentController extends Controller
{
    // Method to handle investment actions
    public function invest(Request $request)
    {
        // Retrieve the user and property information from the request
        $userId = $request->input('user_id');
        $propertyId = $request->input('property_id');
        $investmentAmount = $request->input('investment_amount');

        // Check if the user and property exist
        $user = User::find($userId);
        $property = Property::find($propertyId);

        if (!$user || !$property) {
            return response()->json(['error' => 'User or property not found'], 404);
        }

        // Check if the user has sufficient funds for investment
        if ($user->balance < $investmentAmount) {
            return response()->json(['error' => 'Insufficient funds'], 400);
        }

        // Create a new investment record
        $investment = new Investment();
        $investment->user_id = $userId;
        $investment->property_id = $propertyId;
        $investment->investment_amount = $investmentAmount;
        $investment->status = 'pending'; // Set the initial status as pending
        $investment->save();

        // Update the user's balance
        $user->balance -= $investmentAmount;
        $user->save();

        // Perform any additional actions, such as sending notifications or updating property details

        return response()->json(['message' => 'Investment successful'], 200);
    }

    // Method to show user investments
    public function showInvestments($userId)
    {
        // Retrieve the user's investments
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $investments = $user->investments;

        return view('investments.index', compact('investments'));
    }
}
