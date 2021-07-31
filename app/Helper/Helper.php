<?php
namespace App\Helper;

use Carbon\Carbon;
use App\Models\Role;

class Helper
{
  const managerId = 1;
  const executorId = 2;

  public static function DiffDate($date){
    $diff = Carbon::createFromTimestamp(strtotime($date))->gt(Carbon::now());
    return $diff;
  }

  public static function managerName()
  {
      $manager = Role::find(self::managerId);
      if ($manager)
          return $manager->name;
      return "";
  }

  public static function executorName()
  {
      $executor = Role::find(self::executorId);
      if ($executor)
          return $executor->name;
      return "";
  }


}