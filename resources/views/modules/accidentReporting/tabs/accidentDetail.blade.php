@php use Carbon\Carbon; @endphp
<section class="second-section">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="accidentType">Type of Accident*:</label>
                <select id="accidentType" name="accidentType" class="form-control required">
                    <option value="none">Select Incident type</option>
                </select>
                @error('accidentType')
                <p>{{$message}}</p>

                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <label for="accidentNature">Nature of accident*:</label>
            <select id="accidentNature" name="accidentNature" class="form-control required">
                <option value="none">Select Incident Nature</option>
            </select>
        </div>

        <div class="col-md-6 d-none">
            <div class="form-group">
                <label for="peopleInvolved">Number of people involved:</label>
                <input name="peopleInvolved"
                       type="number"
                       class="form-control required"
                       id="peopleInvolved"
                       placeholder="Enter Number of people Involved"
                />
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="other_people_involved">Other People Involved:</label>
                <select name="other_people_involved"
                        type="text"
                        class="form-control disableVehicle"
                        id="other_people_involved" required>
                    <option selected disabled>-- Select --</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                </select>
                @error('other_people_involved')
                <p>{{$message}}</p>
                @enderror
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">
                <label for="num_passengers">Number of Passengers:</label>
                <input name="num_passengers"
                       type="number"
                       class="form-control required"
                       id="num_passengers"
                       placeholder="Enter Number of Passengers"
                       required/>
            </div>
            @error('num_passengers')
            <p>{{$message}}</p>
            @enderror
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="other_vehicle_involved">Other Vehicles Involved:</label>
                <select name="other_vehicle_involved" type="text"
                        class="form-control disableVehicle"
                        id="other_vehicle_involved" required>
                    <option selected disabled>-- Select --</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                </select>
                @error('property')
                <p>{{$message}}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="death">Death:</label>
                <select name="death"
                        type="text"
                        class="form-control disableVehicle"
                        id="death" required>
                    <option selected disabled>-- Select --</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                </select>
                @error('property')
                <p>{{$message}}</p>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="day_of_week">Day Of The Week:</label>
                <select name="day_of_week" class="form-control required"
                        id="day_of_week" required>
                    <option selected disabled>Select Day Of Week</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>
            @error('num_passengers')
            <p>{{$message}}</p>
            @enderror
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="location">Location:</label>
                <input name="location"
                       type="text"
                       class="form-control required"
                       id="location"
                       placeholder="Enter The Location Of The Accident"
                       required/>
            </div>
            @error('peopleInvolved')
            <p>{{$message}}</p>

            @enderror
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="area">Area:</label>
                <input name="area" type="text" class="form-control required" id="location"
                       placeholder="Enter The Area Of The Accident" required>
            </div>
            @error('peopleInvolved')
            <p>{{$message}}</p>

            @enderror
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="property">Was There Property Damage ?:</label>
                <select name="property" type="text" class="form-control disableVehicle"
                        id="property" required>
                    <option selected disabled>-- Select --</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                </select>
                @error('property')
                <p>{{$message}}</p>

                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group ">
                <label for="date">Date*:</label>
                <div class="input-group">
                    <input name="date" type="date" class="form-control required"
                           onkeydown="return false" id="accident-date"
                           placeholder="00/00/0000"
                           max="{{date('Y-m-d', strtotime( Carbon::now()))}}"
                           min="{{date('Y-m-d', strtotime($minDate))}}"
                           required>
                    <div class="input-group-text">
                        <i class="fa fa-calendar"></i>
                    </div>
                </div>
            </div>
            @error('date')
            <p>{{$message}}</p>

            @enderror
        </div>

        <div class="col-md-6">
            <div class="form-group ">
                <label for="time">Time*:</label>
                <input name="time" type="time" class="form-control required" id="time"
                       placeholder="00:00" required>
            </div>
            @error('time')
            <p>{{$message}}</p>

            @enderror
        </div>
        <div class="col-md-6 options policeNotification">
            <p class="test">Is The Company Driver Guilty ?: </p>
            <label class="checkbox-inline mr-5">
                <input type="radio" id="policeNotification-yes" name="guilty" value="yes">
                <label for="policeNotification-yes">Yes</label>
            </label>
            <label class="checkbox-inline ml-2">
                <input type="radio" id="policeNotification-no" name="guilty" value="no">
                <label for="policeNotification-no">No</label>
            </label>
            @error('guilty')
            <p>{{$message}}</p>

            @enderror
        </div>
    </div>
</section>
