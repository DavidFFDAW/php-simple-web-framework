<?php
interface MiddlewareInterface
{
    public function validate(Request $request, Closure $next): bool;
}
