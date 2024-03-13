<?php
interface MiddlewareInterface
{
    public function validate(Request $request): bool;
}
