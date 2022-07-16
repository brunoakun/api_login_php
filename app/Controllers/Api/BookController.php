<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\BookModel;
use App\Validation\CustomRules;

class BookController extends ResourceController
{
    public function addBook()
    {
        $rules = [
            "isbn" => "required|is_unique[books.isbn]",
            "year" => "required|yearValidate"
        ];

        $messages = [
            "isbn" => [
                "required" => "isbn is required",
				"is_unique" => "isbn already exists"
            ],
            "year" => [
                "required" => "year is required",
                "yearValidate"=>"Año ha de ser posterior a 2010"
            ]
        ];

        if (!$this->validate($rules, $messages)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {

            $emp = new BookModel();

            $data['isbn'] = $this->request->getVar("isbn");
            $data['title'] = $this->request->getVar("title");
            $data['description'] = $this->request->getVar("description");
            $data['author'] = $this->request->getVar("author");
            $data['year'] = $this->request->getVar("year");

            $emp->save($data);

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Book added successfully',
                'data' => []
            ];
        }

        return $this->respond($response);
    }


    public function listBook()
    {
        $libro = new BookModel();

        //log_message('error', $e->getMessage());

        $response = [
            'status' => 200,
            "error" => false,
            'messages' => 'Book list',
            'count' => $libro->countAllResults(),
            'data' => $libro->findAll()
        ];

        return $this->respond($response);
    }


    public function showBook($id_book)
    {
        $libro = new BookModel();
        $data = $libro->find($id_book);
        //$data = $model->where(['id' => $emp_id])->first();

        if (!empty($data)) {

            $response = [
                'status' => 200,
                "error" => false,
                'messages' => 'Single Book data',
                'data' => $data
            ];
        } else {

            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'No Book found',
                'data' => []
            ];
        }

        return $this->respond($response);
    }


    public function updateBook($emp_id)
    {
        $rules = [
            "name" => "required",
            "email" => "required|valid_email|min_length[6]",
            "phone_no" => "required",
        ];

        $messages = [
            "name" => [
                "required" => "Name is required"
            ],
            "email" => [
                "required" => "Email required",
                "valid_email" => "Email address is not in format"
            ],
            "phone_no" => [
                "required" => "Phone Number is required"
            ],
        ];

        if (!$this->validate($rules, $messages)) {

            $response = [
                'status' => 500,
                'error' => true,
                'message' => $this->validator->getErrors(),
                'data' => []
            ];
        } else {

            $emp = new BookModel();

            if ($emp->find($emp_id)) {

                $data['name'] = $this->request->getVar("name");
                $data['email'] = $this->request->getVar("email");
                $data['phone_no'] = $this->request->getVar("phone_no");

                $emp->update($emp_id, $data);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Book updated successfully',
                    'data' => []
                ];
            } else {

                $response = [
                    'status' => 500,
                    "error" => true,
                    'messages' => 'No Book found',
                    'data' => []
                ];
            }
        }

        return $this->respond($response);
    }



    public function deleteBook($id)
    {
        $Libro = new BookModel();
        $data = $Libro->find($id);

        if (!empty($data)) {
            $Libro->delete($id);
            $response = [
                'status' => 200,
                "error" => false,
                'messages' => 'Book deleted successfully',
                'data' => []
            ];
        } else {
            $response = [
                'status' => 500,
                "error" => true,
                'messages' => 'No Book found',
                'data' => []
            ];
        }
        return $this->respond($response);
    }
}
