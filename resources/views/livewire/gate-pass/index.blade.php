<section class="content">
    <x-error-view/>
    <x-content-header pageTitle="Gate Pass"
                      :activeCrumb="'Gate Pass'"
                      :link="'home'"
                      :linkText="'Home'"/>
    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Type</th>
                            <th>Vehicle Reg #</th>
                            <th>Raised By</th>
                            <th>Expires</th>
                            <th>Authorized By</th>
                            <th>Checked By</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        @foreach($gatePasses as $gp)
                            <tr>
                                <td class="align-middle">{{$gp->reference_number}}</td>
                                <td class="align-middle">{{$gp->type->label()}}</td>
                                <td class="align-middle">{{$gp->reg_no}}</td>
                                <td class="align-middle">{{$gp->user->name}}</td>
                                <td class="align-middle">{{$gp->expires_at->toFormattedDateString()}}</td>
                                <td class="align-middle">{{$gp->authorisedBy->name ?? 'Unauthorised'}}</td>
                                <td class="align-middle">{{$gp->checkedBy->name ?? 'Unchecked'}}</td>
                                <td class="align-middle">{!! $gp->status->badge() !!}</td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-light btn-active-light-primary btn-sm dropdown-toggle"
                                                type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" style="">

                                            <li>
                                                <a class="dropdown-item" data-kt-action="edit"
                                                   href="{{route('gate-pass.show', $gp)}}">
                                                    View
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>
