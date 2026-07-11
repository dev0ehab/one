<?php

namespace App\Traits;
use App\Models\LogActivity;
use Request;
trait ReportTrait {
  public static function addToLog($subject) {
    $log             = [];
    $log['subject']  = ' قام ' . auth('admin')->user()->name . ' ب ' . $subject;
    $log['url']      = Request::fullUrl();
    $log['method']   = Request::method();
    $log['ip']       = Request::ip();
    $log['agent']    = Request::header('user-agent');
    $log['admin_id'] = auth('admin')->check() ? auth(guard: 'admin')->id() : 1;

    LogActivity::create($log);
  }

  public static function logActivityLists() {
    return LogActivity::latest()->get();
  }
}