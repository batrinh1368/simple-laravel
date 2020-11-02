@extends('layouts.admin.resources.index')

@section('resource_index')
    <div class="table-responsive list-records">
        <table class="table table-hover table-bordered">
            <thead>
            <tr>
                <th style="width: 20px;">
                    #
                </th>
                <th>{{__a('user_page.name')}}
                    {!! __admin_sortable('name') !!}
                </th>
                <th style="width: 150px">Email
                    {!! __admin_sortable('email') !!}
                </th>
                <th style="width: 100px;">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($records as $record)
                <?php
                $editLink = route($resourceRoutesAlias . '.edit', $record->id);
                $deleteLink = route($resourceRoutesAlias . '.destroy', $record->id);
                $formId = 'formDeleteModel_' . $record->id;
                ?>
                <tr>
                    <td class="text-right">{{$index + 1}}</td>
                    </td>
                    <td class="table-text">
                        <a class="text-black" href="{{ $editLink }}">{{ $record->name }}</a>
                    </td>
                    <td class="table-text">
                        <a class="text-black" href="mailto:{{ $record->email }}">{{ $record->email }}</a>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ $editLink }}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                            <a href="#" class="btn btn-danger btn-sm btnOpenerModalConfirmModelDelete"
                               data-form-id="{{ $formId }}"><i class="fa fa-trash-o"></i></a>
                        </div>

                        <!-- Delete Record Form -->
                        <form id="{{ $formId }}" action="{{ $deleteLink }}" method="POST" style="display: none;"
                              class="hidden form-inline">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
