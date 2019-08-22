<?php

namespace App\Utils;

trait Errors {
    public $badRequest = 'درخواست نامعتبر';
    public $badUsername = 'نام کاربری نامعتبر';
    public $badPassword = 'رمز عبور نامعتبر';
    public $badToken = 'توکن نامعتبر';
    public $badEmail = 'ایمیل نامعتبر';
    public $badTitle = 'عنوان نامعتبر';
    public $badBody = 'پیام نامعتبر';

    public $badDetails = 'جزییات نامعتبر';
}