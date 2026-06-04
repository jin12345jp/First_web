@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>取引確認</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; color: #2c3e50; }
        .confirmation-box { background-color: #d4edda; border: 2px solid #28a745; padding: 15px; border-radius: 4px; margin-bottom: 20px; color: #155724; }
        .info-section { margin-bottom: 30px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; }
        .info-title { font-size: 16px; font-weight: bold; margin-bottom: 10px; color: #2c3e50; }
        .transaction-item { background-color: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 4px; }
        .transaction-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; font-weight: bold; color: #2c3e50; }
        .transaction-date { font-size: 18px; }
        .transaction-amount { font-size: 18px; color: #28a745; }
        .transaction-details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 10px; }
        .detail-row { display: flex; }
        .detail-label { font-weight: bold; width: 100px; }
        .detail-value { flex: 1; }
        .children-list { margin-top: 15px; padding-top: 10px; border-top: 1px solid #ddd; }
        .child-item { background-color: #f9f9f9; padding: 10px; margin-bottom: 8px; border-left: 3px solid #95a5a6; }
        .receipt-image { max-width: 150px; margin-top: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .button-group { display: flex; gap: 10px; margin-top: 30px; }
        .btn-primary { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-primary:hover { background-color: #218838; }
        .btn-secondary { background-color: #6c757d; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-secondary:hover { background-color: #5a6268; }
        .summary-section { background-color: #e7f3ff; border: 1px solid #b3d9ff; padding: 15px; border-radius: 4px; margin-top: 20px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .summary-label { font-weight: bold; }
        .summary-value { color: #0066cc; font-size: 18px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>取引確認</h1>

        <div class="confirmation-box">
            ✓ 以下の内容で登録してもよろしいですか？
        </div>

        <div class="info-section">
            <div class="info-title">取引種別</div>
            <p>{{ $type === 'income' ? '入金' : '出金' }}</p>
        </div>

        <div class="info-section">
            <div class="info-title">取引一覧</div>
            @foreach($parents as $parent)
            <div class="transaction-item">
                <div class="transaction-header">
                    <span class="transaction-date">{{ date('Y年m月d日', strtotime($parent['date'])) }}</span>
                    <span class="transaction-amount">¥{{ number_format($parent['amount']) }}</span>
                </div>

                <div class="transaction-details">
                    <div class="detail-row">
                        <span class="detail-label">費目:</span>
                        <span class="detail-value">{{ $parent['category'] }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">摘要:</span>
                        <span class="detail-value">{{ $parent['description'] }}</span>
                    </div>
                </div>

                @if(!empty($parent['image_path']))
                <div>
                    <strong>レシート画像:</strong>
                    <img src="{{ asset($parent['image_path']) }}" alt="レシート" class="receipt-image">
                </div>
                @endif

                @if(!empty($parent['children']))
                <div class="children-list">
                    <strong>詳細項目:</strong>
                    @foreach($parent['children'] as $child)
                    <div class="child-item">
                        <span>{{ $child['name'] }}</span>
                        <span> × {{ $child['quantity'] }} @ ¥{{ number_format($child['unit_price']) }}</span>
                        <span> = ¥{{ number_format($child['quantity'] * $child['unit_price']) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach

            <div class="summary-section">
                <div class="summary-row">
                    <span class="summary-label">合計金額:</span>
                    <span class="summary-value">¥{{ number_format(array_sum(array_column($parents, 'amount'))) }}</span>
                </div>
            </div>
        </div>

        <form method="post" action="{{ route('transactions.store') }}">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="parents_data" value="{{ json_encode($parents) }}">

            <div class="button-group">
                <button type="submit" class="btn-primary">登録する</button>
                <a href="{{ route('transactions.create') }}" class="btn-secondary">戻る</a>
            </div>
        </form>
    </div>
</body>
</html>
@endsection
