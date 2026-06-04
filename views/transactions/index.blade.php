@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>現金出納帳</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background-color: #f5f5f5; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 24px; font-weight: bold; }
        .btn-add { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; cursor: pointer; }
        .btn-add:hover { background-color: #45a049; }
        .filter-row { background-color: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .filter-row form { display: flex; gap: 15px; align-items: center; flex-wrap: wrap; }
        .filter-row label { display: flex; align-items: center; gap: 5px; }
        .filter-row select { padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-filter { background-color: #008CBA; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-filter:hover { background-color: #007399; }
        .btn-reset { background-color: #f44336; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-reset:hover { background-color: #da190b; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table thead { background-color: #2c3e50; color: white; }
        table th { padding: 12px; text-align: left; border: 1px solid #ddd; }
        table td { padding: 10px; border: 1px solid #ddd; }
        table tbody tr:hover { background-color: #f0f0f0; }
        .month-header { background-color: #ecf0f1; font-weight: bold; }
        .month-header td { background-color: #ecf0f1; }
        .month-subtotal { background-color: #d5f4e6; font-weight: bold; }
        .summary-row { background-color: #a9dfbf; font-weight: bold; }
        .parent-row { }
        .child-row { background-color: #f9f9f9; font-size: 0.9em; }
        .col-date { width: 80px; }
        .col-w { width: 40px; text-align: center; }
        .col-w.sun { color: red; }
        .col-w.sat { color: blue; }
        .col-amount { width: 100px; text-align: right; }
        .col-balance { width: 100px; text-align: right; font-weight: bold; }
        .col-actions { width: 150px; }
        .toggle-btn { background: none; border: none; cursor: pointer; font-size: 12px; padding: 2px 5px; }
        .receipt-image { max-width: 100px; max-height: 100px; cursor: pointer; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; }
        .btn-action { padding: 5px 10px; margin: 2px; text-decoration: none; border-radius: 3px; font-size: 12px; display: inline-block; }
        .btn-edit { background-color: #2196F3; color: white; }
        .btn-delete { background-color: #f44336; color: white; border: none; cursor: pointer; }
        .btn-delete:hover { background-color: #da190b; }
        .inline-form { display: inline; }
        .item-name { font-weight: bold; }
        .item-details { color: #666; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-controls">
            <span>現金出納帳</span>
            <a href="{{ route('transactions.create') }}" class="btn-add">＋ 新規入力画面へ</a>
        </div>

        @if ($message = Session::get('success'))
            <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div style="background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                {{ $message }}
            </div>
        @endif

        <div class="filter-row">
            <form method="get" action="{{ route('transactions.index') }}">
                <label>年度:
                    <select name="fiscal_year">
                        <option value="all" @if($selectedFiscalYear == 'all') selected @endif>全て</option>
                        @foreach($availableFiscalYears as $fiscal)
                            <option value="{{ $fiscal['key'] }}" @if($selectedFiscalYear == $fiscal['key']) selected @endif>{{ $fiscal['label'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label>月:
                    <select name="month">
                        <option value="">全て</option>
                        @foreach($availableMonths as $month)
                            <option value="{{ $month['key'] }}" @if($selectedMonth == $month['key']) selected @endif>{{ $month['label'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label>費目:
                    <select name="category">
                        <option value="">全て</option>
                        @foreach($availableCategories as $category)
                            <option value="{{ $category }}" @if($selectedCategory == $category) selected @endif>{{ $category }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="btn-filter">表示</button>
                <button type="button" class="btn-reset" onclick="window.location.href='{{ route('transactions.index') }}'">リセット</button>
            </form>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-date">日付</th>
                    <th class="col-w">曜</th>
                    <th>摘要</th>
                    <th>メモ</th>
                    <th>費目</th>
                    <th class="col-amount">入金</th>
                    <th class="col-amount">出金</th>
                    <th class="col-balance">残高</th>
                    <th class="col-actions">操作</th>
                </tr>
            </thead>
            <tbody>
                @php $total_income = 0; $total_expense = 0; @endphp

                @foreach($groupedTransactions as $group)
                    <tr class="month-header">
                        <td colspan="9">{{ $group['label'] }}</td>
                    </tr>
                    @foreach($group['rows'] as $row)
                    <tr class="parent-row {{ empty($row->children) ? 'no-children' : '' }}">
                        <td class="col-date">
                            @if(!empty($row->children))
                            <button type="button" class="toggle-btn" onclick="toggleChildren({{ $row->id }})">▶</button>
                            @endif
                            {{ $row->transaction_date->format('m/d') }}
                        </td>
                        <td class="col-w @if($row->transaction_date->format('w') == 0) sun @elseif($row->transaction_date->format('w') == 6) sat @endif">
                            {{ $row->transaction_date->format('D') }}
                        </td>
                        <td>
                            {{ $row->description }}
                            @if(!empty($row->image_path))
                                <div>
                                    <img src="{{ asset($row->image_path) }}" alt="レシート画像" class="receipt-image" onclick="toggleImageModal(this)">
                                </div>
                            @endif
                        </td>
                        <td>{{ $row->memo }}</td>
                        <td>{{ $row->category }}</td>
                        <td class="col-amount">@if($row->income > 0){{ number_format($row->income) }}@endif</td>
                        <td class="col-amount">@if($row->expense > 0){{ number_format($row->expense) }}@endif</td>
                        <td class="col-balance">{{ number_format($row->calculated_balance) }}</td>
                        <td class="col-actions">
                            <a href="{{ route('transactions.edit', $row->id) }}" class="btn-action btn-edit">編集</a>
                            <form method="post" action="{{ route('transactions.destroy', $row->id) }}" class="inline-form" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('このデータを削除しますか？');">削除</button>
                            </form>
                        </td>
                    </tr>
                    @foreach($row->children as $child)
                    <tr class="child-row" data-parent-id="{{ $row->id }}" style="display:none;">
                        <td class="col-date"></td>
                        <td class="col-w"></td>
                        <td colspan="4">
                            <span class="item-name">商品: {{ $child->item_name }}</span>
                            <span class="item-details">× {{ $child->quantity }} @ ¥{{ number_format($child->unit_price) }}</span>
                        </td>
                        <td class="col-amount">{{ number_format($child->quantity * $child->unit_price) }}</td>
                        <td class="col-balance"></td>
                        <td class="col-actions"></td>
                    </tr>
                    @endforeach
                    @php $total_income += $row->income; $total_expense += $row->expense; @endphp
                    @endforeach
                    <tr class="month-subtotal">
                        @if($selectedMonth != '')
                        <td colspan="5" style="text-align:right;">{{ $group['label'] }} 合計</td>
                        @else
                        <td colspan="5" style="text-align:right;">{{ $group['label'] }} 月次</td>
                        @endif
                        <td class="col-amount">{{ number_format($group['total_income']) }}</td>
                        <td class="col-amount">{{ number_format($group['total_expense']) }}</td>
                        <td class="col-balance">{{ number_format($group['last_balance']) }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="summary-row">
                    @if($selectedMonth != '')
                    <td colspan="5" style="text-align:right;">計</td>
                    @elseif($selectedCategory != '')
                    <td colspan="5" style="text-align:right;">{{ $selectedCategory }}  合計</td>
                    @elseif($selectedFiscalYear == 'all')
                    <td colspan="5" style="text-align:right;">合計</td>
                    @else
                    <td colspan="5" style="text-align:right;">{{ $selectedFiscalYear }}年度  合計</td>
                    @endif
                    <td class="col-amount">{{ number_format($total_income) }}</td>
                    <td class="col-amount">{{ number_format($total_expense) }}</td>
                    <td class="col-balance">{{ number_format($total_income - $total_expense) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        function toggleChildren(parentId) {
            const rows = document.querySelectorAll(`[data-parent-id="${parentId}"]`);
            rows.forEach(row => {
                row.style.display = row.style.display === 'none' ? '' : 'none';
            });
        }

        function toggleImageModal(img) {
            const modal = document.createElement('div');
            modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.7);display:flex;align-items:center;justify-content:center;z-index:1000;';
            const largeImg = document.createElement('img');
            largeImg.src = img.src;
            largeImg.style.cssText = 'max-width:90%;max-height:90%;cursor:pointer;';
            modal.appendChild(largeImg);
            modal.onclick = () => modal.remove();
            largeImg.onclick = (e) => e.stopPropagation();
            document.body.appendChild(modal);
        }
    </script>
</body>
</html>
@endsection
