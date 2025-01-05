@include('errors.custom',
 ['code' => $exception->getStatusCode(), 'message' => \App\Exceptions\Handler::getMessage($exception) ])
