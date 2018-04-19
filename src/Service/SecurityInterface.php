<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

interface SecurityInterface
{
    public function handleSubmittedRegistrationForm(FormInterface $form): RedirectResponse;
}