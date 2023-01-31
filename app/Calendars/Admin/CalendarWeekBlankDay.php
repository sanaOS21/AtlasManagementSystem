<?php

namespace App\Calendars\Admin;

class CalendarWeekBlankDay extends CalendarWeekDay
{

  // 余白用カレンダークラスの作成
  function getClassName()
  {
    return "day-blank";
  }

  function render()
  {
    return '';
  }

  function everyDay()
  {
    return '';
  }

  function dayPartCounts($ymd = null)
  {
    return '';
  }

  function dayNumberAdjustment()
  {
    return '';
  }
}
