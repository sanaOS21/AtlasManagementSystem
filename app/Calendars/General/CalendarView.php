<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView
{

  private $carbon;
  function __construct($date)
  {
    // new Carbon()= 現在日付のインスタンスができる
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th>月</th>';
    $html[] = '<th>火</th>';
    $html[] = '<th>水</th>';
    $html[] = '<th>木</th>';
    $html[] = '<th>金</th>';
    $html[] = '<th>土</th>';
    $html[] = '<th>日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();
      foreach ($days as $day) {
        // copy　複製
        $startDay = $this->carbon->copy()->format("Y-m-01");
        $toDay = $this->carbon->copy()->format("Y-m-d");

        if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          // past-day記入
          $html[] = '<td class="calendar-td past-day">';
        } else {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . '">';
        }
        $html[] = $day->render();

        // 予約済みであれば下記対応
        if (in_array($day->everyDay(), $day->authReserveDay())) {
          $reservePartId = $day->authReserveDate($day->everyDay())->first()->setting_part;
          if ($reservePartId == 1) {
            $reservePart = "リモ1部";
          } else if ($reservePartId == 2) {
            $reservePart = "リモ2部";
          } else if ($reservePartId == 3) {
            $reservePart = "リモ3部";
          }
          //$startDay...月初
          if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px"></p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {
            // キャンセルボタンを設置
            // class=""変更してみた（キャンセルモーダルのために）
            // value=""削除
            $html[] = '<button type="submit" class="cancel-modal-open btn btn-danger p-0 w-75" name="" style="font-size:12px" delete_date="' . $day->authReserveDate($day->everyDay())->first()->setting_reserve . '" delete_part="' . $reservePart . '" delete-part-id="' . $reservePartId . '"> ' . $reservePart . '</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }
          // 予約していなければ！
        } else if ($startDay <= $day->everyDay() && $toDay >= $day->everyDay()) {
          $html[] = '<p>受付終了</p>';
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
        } else {
          $html[] = $day->selectPart($day->everyDay());
        }
        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    // 月初月末
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();

    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
