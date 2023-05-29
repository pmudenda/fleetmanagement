/**
 * Authored by Mwape Daka
 * Edited By Lovemore Daka
 * Version: 1.0.0
 * */
$(document).ready(function () {
    let context = "/";
    let $body = $('body');
    let sessionModal = `<div class="modal fade" id="inactivity-modal" tabindex="-1" data-table-id="x" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 50%;">
        <div class="modal-content">
            <div class="modal-header bg-zesco">
                <h5 class="modal-title text-center" style="width:100%">Inactive Session</h5>
            </div>
            <div class="modal-body p-b-0">
                <h6 class="text-center mt-5">Your account has been inactive for some time. Your session will expire in</h6>
                <div class="timer-container">
                    <div class="setters">
                        <div class="minutes-set">
                            <button class="d-none" data-setter="minutes-plus">+</button>
                            <button class="d-none" data-setter="minutes-minus">-</button>
                        </div>
                        <div class="seconds-set">
                            <button class="d-none" data-setter="seconds-plus">+</button>
                            <button class="d-none" data-setter="seconds-minus">-</button>
                        </div>
                    </div>
                    <div class="circle">
                        <svg width="300" viewBox="0 0 220 220" xmlns="http://www.w3.org/2000/svg">
                            <g transform="translate(110,110)">
                                <circle r="100" class="e-c-base" />
                                <g transform="rotate(-90)">
                                    <circle r="100" class="e-c-progress" />
                                    <g id="e-pointer">
                                        <circle cx="100" cy="0" r="0" class="e-c-pointer" />
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <div class="controlls">
                        <div class="display-remain-time">00:30</div>
                        <button class="play d-none" id="pause"></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger mr-2" data-dismiss="modal" id="pop-up-sign-out">Log Out</button>
                <button type="button" class="btn btn-outline-success" data-dismiss="modal" id="pop-up-continue">Stay Connected</button>
            </div>
        </div>
    </div>
    </div>`;

    $body.append(sessionModal);

    // Inactivity pop-up
    window.COUNT_DOWN_TIME_MINUTES = 5 * 60; // 5 minutes
    window.POP_UP_TIME_MILLISECONDS = 8 * 60 * 1000; // 8 minutes
    window.sessionInactiveSeconds = window.POP_UP_TIME_MILLISECONDS;
    window.POLLING_INTERVAL_MILLISECONDS = 60 * 60 * 1000; // 1 minute
    window.refreshSession = false;
    window.isTimerVisible = false;

    window.intervalFunction = setInterval(
        function () {
            window.sessionInactiveSeconds = window.sessionInactiveSeconds - window.POLLING_INTERVAL_MILLISECONDS;
            if (window.sessionInactiveSeconds <= 0) {
                window.refreshSession = true;
                window.showInactivityPopUp();
            }
            if (!!window.refreshSession) {
                window.getSessionStatus();
            }
        }, POLLING_INTERVAL_MILLISECONDS);

    // reset inactivity session, there is activity on the page
    $body.on('change', 'input,select,textarea', function () {
        window.refreshSession = true;
    });

    $('#pop-up-sign-out').on('click', function () {
        window.isTimerVisible = false;
        clearInterval(window.intervalTimer);
        window.logoutUser();
    });

    $('#pop-up-continue').on('click', function () {
        window.refreshSession = true;
        window.isTimerVisible = false;
        clearInterval(window.intervalTimer);
        window.getSessionStatus();
    });

    window.showInactivityPopUp = function () {
        if (!window.isTimerVisible) {
            $("#inactivity-modal").modal('show');
            window.timer(window.COUNT_DOWN_TIME_MINUTES, window.logoutUser);
            window.isTimerVisible = true;
        }
    }
    window.logoutUser = function () {
        //window.location.href = context + '/logout';
        $('a[href$="logout"]').trigger('click');
    };

    window.getSessionStatus = function () {
        console.log("Check Session Status");
        window.showLoader = false;
        fetch(
            document.querySelector("#sessionStatusUrl").value,
            {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                body: {},
                //signal: window.abortControllers[endpointId].signal,
                referrer: window.baseUrl,
                mode: 'cors',
                credentials: 'same-origin',
            }
        )
            .then((response) => {
                if (!response.ok) {
                    window.logoutUser();
                    return;
                    //throw new Error(`HTTP error! Status: ${response.status}`);
                }

                return response.json();
            })
            .then(response => {
                console.log(response);
                let responsePayload = null;
                /*if (response.hasOwnProperty('responseJSON')) {   } else {}*/
                responsePayload = response;

                if (responsePayload.state === 'active') {
                    window.showLoader = true;
                    window.sessionInactiveSeconds = window.POP_UP_TIME_MILLISECONDS;
                    window.refreshSession = false;
                } else {
                    window.logoutUser();
                }
                $("#inactivity-modal").modal('hide');
                window.loaderVisible = true;
            })
            .catch(function (error) {
                window.logoutUser();
            });
    }

    window.getCsrfToken = function () {
        let tokenTag = document.head.querySelector('meta[name="csrf-token"]');
        return tokenTag ? tokenTag.content : null !== window._token ? window._token : $('input[name="_token"]').val();
    }

    /* =================================================================================== */
    //circle start
    window.timerProgressBar = document.querySelector('.e-c-progress');
    window.timerPointer = document.getElementById('e-pointer');
    window.timerLength = Math.PI * 2 * 100;

    timerProgressBar.style.strokeDasharray = timerLength;

    window.update = function (value, timePercent) {
        console.log("Progress Timer Update");
        timerProgressBar.style.strokeDashoffset = -timerLength - timerLength * value / (timePercent);
        timerPointer.style.transform = `rotate(${360 * value / (timePercent)}deg)`;
    };

    //circle ends
    window.displayOutput = document.querySelector('.display-remain-time');
    window.setterBtns = document.querySelectorAll('button[data-setter]');
    window.intervalTimer = 0;
    window.timeLeft = 0;
    window.wholeTime = 5 * 60; // manage this to set the whole time
    window.isStarted = false;

    window.changeWholeTime = function (seconds) {
        if ((window.wholeTime + seconds) > 0) {
            window.wholeTime += seconds;
            window.update(window.wholeTime, window.wholeTime);
        }
    };

    window.timer = function (seconds, callback) { //counts time, takes seconds
        let remainTime = Date.now() + (seconds * 1000);
        window.displayTimeLeft(seconds);

        window.intervalTimer = setInterval(function () {
            console.log("Set Interval");
            window.timeLeft = Math.round((remainTime - Date.now()) / 1000);
            if (window.timeLeft < 0) {
                clearInterval(window.intervalTimer);
                //window.isStarted = false;
                //window.displayTimeLeft(window.wholeTime);
                if (!!callback) {
                    callback();
                }
                return;
            }
            window.displayTimeLeft(window.timeLeft);
        }, 1000);
    };
    window.displayTimeLeft = function (timeLeft) {
        //console.log("Calling Display Time Left");
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;
        window.displayOutput.textContent = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        window.update(timeLeft, window.wholeTime);
    };
    window.update(window.wholeTime, window.wholeTime); //refreshes progress bar
    window.displayTimeLeft(window.wholeTime);
    /* =================================================================================== */

});
