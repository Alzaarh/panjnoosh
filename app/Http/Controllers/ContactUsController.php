<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendContactUsEmailToAdmin;
use Illuminate\Support\Facades\DB;
use App\Utils\Errors;

class ContactUsController extends Controller {
    use Errors;
    private const ADMIN_TABLE = 'admin_info';
    //Create contact us email
    public function create(Request $request) {
        $adminEmail = DB::table(self::ADMIN_TABLE)->select('email')->first();
        if($adminEmail) {
            $validatedInput = $this->validateCreate($request);
            $validatedInput['adminEmail'] = $adminEmail;
            dispatch(new SendContactUsEmailToAdmin($validatedInput));
            return response()->json([], 200);
        }
        else {
            return response()->json(['errors' => ['contact_us' => 'forbidden']], 400);
        }
    }
    //Validate user input for creating contact us email
    private function validateCreate(Request $request) {
        $rules = [
            'email' => 'bail|required|string|email|max:255',
            'title' => 'bail|required|string|max:255',
            'body' => 'bail|required|string|max:1000',
        ];
        return $this->validate($request, $rules, [
            'email.*' => $this->badEmail,
            'title.*' => $this->badTitle,
            'body.*' => $this->badBody,
        ]);
    }
}
