<?php

class AuthMiddleware implements MiddlewareInterface
{
      public function validate(Request $request): bool
      {
            // $request->setExtra('user', 'admin');
            $request->redirect('/');
            return true;
      }
}
