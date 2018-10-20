<?php
use \Illuminate\Support\Facades\DB;
$ss = DB::select(DB::raw("select * from user_videos"));
dd($ss);
//$con = mysqli_connect();
