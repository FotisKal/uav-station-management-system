@auth
    @if(Auth::user()->debug == true)
        <div class="container-fluid">
            <div class="debug my-5">
                {!! str_replace(chr(10), '<br>', e(show_stats())) !!}
                    <?php
                    echo 'Total queries executed: '.count(DB::getQueryLog()).' <br>';
                    foreach (DB::getQueryLog() as $v) {
                        echo e(vsprintf(str_replace('?', '%s', $v['query']), $v['bindings']).' | '.$v['time'].' ms').'<br>';
                    }
                    ?>
            </div>
        </div>
    @endif
@endauth
