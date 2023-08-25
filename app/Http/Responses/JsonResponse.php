<?php

namespace App\Http\Responses;

class JsonResponse
{
    private mixed $state;
    private mixed $request;
    private mixed $payload;
    private mixed $redirectUrl;
    private mixed $message;

    /**
     * @return mixed
     */
    public function getState(): mixed
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState(mixed $state): void
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getRequest(): mixed
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest(mixed $request): void
    {
        $this->request = $request;
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }

    public function setPayload(mixed $payload): void
    {
        $this->payload = $payload;
    }

    public function getRedirectUrl(): mixed
    {
        return $this->redirectUrl;
    }

    public function setRedirectUrl(mixed $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function getMessage(): mixed
    {
        return $this->message;
    }

    public function setMessage(mixed $message): void
    {
        $this->message = $message;
    }
}
