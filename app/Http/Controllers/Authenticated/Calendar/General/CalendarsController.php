<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarsController extends Controller
{
    public function show()
    {
        // ↓timeを使って現在時刻を渡す。
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    {
        // CalendarView.phpのreservePartsフォームから受け取る
        DB::beginTransaction();
        try {
            // 予約する日を取得
            $getPart = $request->getPart;
            // 予約する部を取得
            $getDate = $request->getData;
            // ?
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach ($reserveDays as $key => $value) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
    // キャンセル
    public function delete(Request $request)
    {
        // CalendarView.phpのdeletePartsフォームから受け取る
        DB::beginTransaction();
        try {
            // 予約する日を取得
            $getDate = $request->delete_date;

            // 予約する部を取得
            $getPart = $request->delete_part;
            dd($getPart);
            $reserve_settings = ReserveSettings::where('setting_reserve', $getDate)->where('setting_part', $getPart)->first();
            $reserve_settings->increment('limit_users');
            $reserve_settings->users()->detach(Auth::id());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
