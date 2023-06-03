<div class="container-fluid">
    <div class="row" data-form-url="{{route("job_card.accessories.checkin")}}" data-model-name="Accessories">
        <input type="hidden" value="{{$details->job_card_no ?? 0}}" name="job_card_voucher"/>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="row">

                <div class="col">
                    <table
                        class="table table-row-dashed align-middle gs-0 table-bordered">
                        <thead>
                        <tr class="bg-dark">
                            <th class="pl-2">Item</th>
                            <th>Present</th>
                            <th class="pr-2">Not Present</th>
                            <th class="pr-2">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accessories as $key => $accessory)
                            @if(($key%2) == 0)
                                <tr>
                                    <td class="pl-2"
                                        style="width: 35%;">{{$accessory->name}}</td>
                                    <td><input type="radio" value="YES" required
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td><input type="radio" value="NO" required
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td style="width: 45%;">
                                        <input typeof="text"
                                               name="comment_{{str_replace(' ','', $accessory->code)}}"
                                               class="form-control form-control-sm"/>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col">
                    <table
                        class="table table-row-dashed align-middle gs-0 table-bordered">
                        <thead>
                        <tr class="bg-dark">
                            <th class="pl-2">Item</th>
                            <th>Present</th>
                            <th class="pr-2">Not Present</th>
                            <th class="pr-2">Remarks</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accessories as $key => $accessory)
                            @if(($key%2) != 0)
                                <tr>
                                    <td class="pl-2" style="width: 35%;">
                                        {{$accessory->name}}
                                    </td>
                                    <td><input type="radio" required value="YES"
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td><input type="radio" required value="NO"
                                               name="field_{{str_replace(' ','', $accessory->code)}}">
                                    </td>
                                    <td style="width: 45%;">
                                        <input typeof="text"
                                               name="comment_{{str_replace(' ','', $accessory->code)}}"
                                               class="form-control form-control-sm">
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
