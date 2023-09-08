<?php

namespace App\Http\Responses;

class FleetMasterJsonResponse
{
    private string $state;
    private mixed $request;
    private mixed $payload;
    private mixed $redirectUrl;
    private mixed $message;
    private bool $success;

    public function __construct($state, $success, $message, $payload = null, $redirectUrl = null)
    {
        $this->state = $state;
        $this->success = $success;
        $this->message = $message ?? "";
        $this->payload = $payload ?? [];
        $this->redirectUrl = $redirectUrl ?? "";
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState(string $state): void
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
