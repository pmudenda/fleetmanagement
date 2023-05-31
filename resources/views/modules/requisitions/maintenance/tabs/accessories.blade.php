<div class="container-fluid mt-5">
    <div class="row" data-form-url="{{route("process.job_card")}}" data-model-name="Accessories">
        <div class="col-xs-12 col-sm-9 col-md-8">
            <div class="container-fluid mt-5">
                <div class="row">
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
                                                           name="{{str_replace(' ','', $accessory->code)}}">
                                                </td>
                                                <td><input type="radio" value="NO" required
                                                           name="{{str_replace(' ','', $accessory->code)}}">
                                                </td>
                                                <td style="width: 45%;">
                                                    <input typeof="text"
                                                           name="COMMENT_{{str_replace(' ','', $accessory->code)}}"
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
                                                           name="{{str_replace(' ','', $accessory->code)}}">
                                                </td>
                                                <td><input type="radio" required value="NO"
                                                           name="{{str_replace(' ','', $accessory->code)}}">
                                                </td>
                                                <td style="width: 45%;">
                                                    <input typeof="text"
                                                           name="COMMENT_{{str_replace(' ','', $accessory->code)}}"
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
        </div>
    </div>
</div>
