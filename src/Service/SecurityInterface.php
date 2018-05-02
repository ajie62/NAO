<?php

namespace App\Service;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

interface SecurityInterface
{
    public function handleSubmittedRegistrationForm(FormInterface $form, FlashBagInterface $flashBag): RedirectResponse;
}