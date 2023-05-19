@php
    $tableClasses  = ["table", "table-striped", "bg-white"];
    $booleanFields = isset($booleanFields) ? $booleanFields : [];
    $clickable     = isset($clickable) ? $clickable : False;
    $data          = isset($data) ? $data : [];
    $loading       = isset($loading) ? $loading : False;
    $pagination    = isset($pagination) ? $pagination : True;
    $perPage       = isset($perPage) ? max($perPage, 1) : 15;
    $page          = isset($page) ? min($page, (int) floor(count($data)/$perPage)) : 0;
    if ($pagination) {
        $rows = array_slice($data, $page*$perPage, $perPage);
    } else {
        $rows = $data;
    }
    if (isset($bordered) && $bordered) {
        $tableClasses[] = "table-bordered";
    }
    if ($clickable) {
        $tableClasses[] = "table-hover table-cursor-pointer";
    }
    if (isset($small) && $small) {
        $tableClasses[] = "table-sm";
    }
    $tableSettings = [
        "booleanFields"  => $booleanFields,
        "clickable"      => $clickable,
        "columns"        => $columns,                                               
        "dateIndex"      => isset($dateIndex) ? $dateIndex : Null,
        "keywordIndices" => isset($keywordIndices) ? $keywordIndices : [],
        "page"           => isset($page) ? $page : 0,
        "pagination"     => isset($pagination) ? $pagination : True,
        "perPage"        => isset($perPage) ? $perPage : 15
    ];
@endphp
<div data-table="{{ $name }}">
    <div class="table-responsive-xl">
        <table class="{{ implode(' ', $tableClasses) }}">
            <thead>
                @foreach ($columns as $col)
                    <th scope="col">{{ __("tables.$name.$col") }}</th>
                @endforeach
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr data-index="{{ $loop->index + ($pagination ? $page*$perPage : 0) }}">
                        @foreach ($columns as $col)
                            @if ($loop->first)
                                <th scope="row">{{ $row[$col] }}</th>
                            @else
                                <td>
                                    {!! $row[$col] !!}
                                </td>
                            @endif
                        @endforeach
                        <td>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="text-center">
        <div class="table-loading lds-dual-ring m-auto" {!! !$loading ? 'style="display: none;"' : "" !!}><div></div><div></div></div>
    </div>
    @if ($pagination)
        <nav>
            <ul class="pagination">
                <li class="page-item{{ $page == 0 ? " disabled" : "" }}">
                    <button class="page-link" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </button>
                </li>
                <li class="page-item{{ $page == floor(count($data)/$perPage) ? " disabled" : "" }}">
                    <button class="page-link" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </button>
                </li>
            </ul>
        </nav>
    @endif
</div>
@push("scripts")
    <script>
        $(document).ready(function() {
            table("{{ $name }}").setup({!! json_encode($tableSettings) !!}).setData({!! json_encode($data) !!});
        });
    </script>
@endpush