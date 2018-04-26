<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\DB;

use App\Models\GuestBook as GuestBookModel;

/**
 * Description of GuestBook
 *
 * @author brooksie
 */
class GuestBook 
{ 
    /**
     * List action, invokes list request on an eloquent model with ordering, 
     * paging parameters
     * 
     * @param string $order
     * @param string $orderDir
     * @param int $limit
     * @param int $offset
     * @return IlluminateResponse
     */
    public function listAction() : IlluminateResponse
    {
        $results = DB::select("SELECT * FROM guestbook");                ;
        return Response($results);
    }     
    
    /**
     * Add record
     * 
     * @param Request $request
     * @return IlluminateResponse
     */
    public function addEntryAction(Request $request) : IlluminateResponse
    {
        $fieldMeta = [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:admin_users',
            'comments'  => 'required|string'
        ];   
                
        $errors = $this->validate($request);
        if (!empty($errors)) {
            return Response($errors);
        }
        
        DB::insert(
            'INSERT INTO guestbook (name, email, comment, created_at) values (?, ?, ?, ?)', 
            [
                $request->input('name'),
                $request->input('email'),
                $request->input('comment'),
                new \DateTimeImmutable()
            ]
        );
        
        return $this->listAction();
    }
    
    /**
     * Validate input
     * 
     * @param Request $request
     * @return array
     */
    private function validate(Request $request) : array
    {
        $errors = [];
        
        if (strlen($request->input('name')) < 0) {
            $errors['name'] = 'name required';
        }
        
        if (!filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'email not valid';
        }

        if (strlen($request->input('comments')) < 0) {
            $errors['comments'] = 'comment required';
        }        
        
        return $errors;
    }
    
}
