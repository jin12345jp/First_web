@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>取引登録</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; background-color: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; background-color: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; color: #2c3e50; }
        .error-message { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 12px; border-radius: 4px; margin-bottom: 20px; }
        .form-section { margin-bottom: 30px; padding: 15px; background-color: #f9f9f9; border: 1px solid #ddd; border-radius: 4px; }
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #2c3e50; }
        .form-row { display: flex; gap: 15px; margin-bottom: 15px; flex-wrap: wrap; }
        .form-group { flex: 1; min-width: 150px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        .form-group input,
        .form-group select,
        .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        .form-group textarea { min-height: 80px; resize: vertical; }
        .parent-transaction { background-color: #ecf0f1; padding: 15px; margin-bottom: 15px; border-left: 4px solid #3498db; }
        .parent-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .parent-header h3 { color: #2c3e50; }
        .btn-remove-parent { background-color: #e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
        .btn-remove-parent:hover { background-color: #c0392b; }
        .children-section { background-color: white; padding: 10px; border-radius: 4px; margin-top: 15px; }
        .child-item { background-color: #ecf0f1; padding: 10px; margin-bottom: 10px; border-radius: 4px; border-left: 3px solid #95a5a6; }
        .child-controls { display: flex; gap: 10px; margin-top: 10px; }
        .btn-add-child { background-color: #27ae60; color: white; padding: 5px 15px; border: none; border-radius: 3px; cursor: pointer; }
        .btn-add-child:hover { background-color: #229954; }
        .btn-remove-child { background-color: #e74c3c; color: white; padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
        .btn-remove-child:hover { background-color: #c0392b; }
        .button-group { display: flex; gap: 10px; margin-top: 30px; }
        .btn-primary { background-color: #3498db; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-primary:hover { background-color: #2980b9; }
        .btn-secondary { background-color: #95a5a6; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .btn-secondary:hover { background-color: #7f8c8d; }
        .btn-add-transaction { background-color: #27ae60; color: white; padding: 12px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 20px; }
        .btn-add-transaction:hover { background-color: #229954; }
        .type-selector { margin-bottom: 20px; }
        .type-selector label { margin-right: 20px; font-weight: bold; }
        .type-selector input[type="radio"] { margin-right: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>取引登録</h1>

        @if($errors->any())
            <div class="error-message">
                <strong>エラーがあります:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!empty($error))
            <div class="error-message">{{ $error }}</div>
        @endif

        <form method="post" action="{{ route('transactions.confirm') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-section">
                <div class="section-title">取引種別</div>
                <div class="type-selector">
                    <label>
                        <input type="radio" name="type" value="expense" @if($type == 'expense') checked @endif> 出金
                    </label>
                    <label>
                        <input type="radio" name="type" value="income" @if($type == 'income') checked @endif> 入金
                    </label>
                </div>
            </div>

            <div id="transactions-container">
                @foreach($parents as $idx => $parent)
                <div class="parent-transaction">
                    <div class="parent-header">
                        <h3>取引 {{ $idx + 1 }}</h3>
                        @if($idx > 0)
                        <button type="button" class="btn-remove-parent" onclick="removeParent(this)">削除</button>
                        @endif
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>日付</label>
                            <input type="date" name="parent_date[]" value="{{ $parent['date'] }}" required>
                        </div>
                        <div class="form-group">
                            <label>金額</label>
                            <input type="number" name="parent_amount[]" value="{{ $parent['amount'] }}" min="0" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>費目</label>
                            <input type="text" name="parent_category[]" value="{{ $parent['category'] }}" placeholder="例: 食費" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>摘要</label>
                            <input type="text" name="parent_description[]" value="{{ $parent['description'] }}" placeholder="例: スーパーで食材購入" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>レシート画像</label>
                            <input type="file" name="parent_image[]" accept="image/jpeg,image/png,image/gif,application/pdf">
                        </div>
                    </div>

                    <div class="children-section">
                        <div style="margin-bottom: 10px; font-weight: bold;">詳細項目 (オプション)</div>
                        <div class="child-items-container" data-parent-idx="{{ $idx }}">
                            @foreach($parent['children'] as $childIdx => $child)
                            <div class="child-item">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>商品名</label>
                                        <input type="text" name="child_name[{{ $idx }}][]" value="{{ $child['name'] }}" placeholder="例: りんご">
                                    </div>
                                    <div class="form-group">
                                        <label>数量</label>
                                        <input type="number" name="child_qty[{{ $idx }}][]" value="{{ $child['quantity'] }}" min="1">
                                    </div>
                                    <div class="form-group">
                                        <label>単価</label>
                                        <input type="number" name="child_price[{{ $idx }}][]" value="{{ $child['unit_price'] }}" min="0">
                                    </div>
                                </div>
                                <button type="button" class="btn-remove-child" onclick="removeChild(this)">削除</button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-add-child" onclick="addChild({{ $idx }})">+ 項目追加</button>
                    </div>
                </div>
                @endforeach
            </div>

            <button type="button" class="btn-add-transaction" onclick="addParent()">+ 取引を追加</button>

            <div class="button-group">
                <button type="submit" class="btn-primary">確認画面へ</button>
                <a href="{{ route('transactions.index') }}" class="btn-secondary">キャンセル</a>
            </div>
        </form>
    </div>

    <script>
        let parentCount = {{ count($parents) }};

        function addParent() {
            const container = document.getElementById('transactions-container');
            const today = document.querySelector('input[name="parent_date[]"]')?.value || new Date().toISOString().split('T')[0];
            const html = `
                <div class="parent-transaction">
                    <div class="parent-header">
                        <h3>取引 ${parentCount + 1}</h3>
                        <button type="button" class="btn-remove-parent" onclick="removeParent(this)">削除</button>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>日付</label>
                            <input type="date" name="parent_date[]" value="${today}" required>
                        </div>
                        <div class="form-group">
                            <label>金額</label>
                            <input type="number" name="parent_amount[]" value="" min="0" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>費目</label>
                            <input type="text" name="parent_category[]" value="" placeholder="例: 食費" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="flex: 1;">
                            <label>摘要</label>
                            <input type="text" name="parent_description[]" value="" placeholder="例: スーパーで食材購入" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>レシート画像</label>
                            <input type="file" name="parent_image[]" accept="image/jpeg,image/png,image/gif,application/pdf">
                        </div>
                    </div>

                    <div class="children-section">
                        <div style="margin-bottom: 10px; font-weight: bold;">詳細項目 (オプション)</div>
                        <div class="child-items-container" data-parent-idx="${parentCount}">
                        </div>
                        <button type="button" class="btn-add-child" onclick="addChild(${parentCount})">+ 項目追加</button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            parentCount++;
        }

        function removeParent(btn) {
            btn.closest('.parent-transaction').remove();
        }

        function addChild(parentIdx) {
            const container = document.querySelector(`[data-parent-idx="${parentIdx}"]`);
            const html = `
                <div class="child-item">
                    <div class="form-row">
                        <div class="form-group">
                            <label>商品名</label>
                            <input type="text" name="child_name[${parentIdx}][]" value="" placeholder="例: りんご">
                        </div>
                        <div class="form-group">
                            <label>数量</label>
                            <input type="number" name="child_qty[${parentIdx}][]" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label>単価</label>
                            <input type="number" name="child_price[${parentIdx}][]" value="0" min="0">
                        </div>
                    </div>
                    <button type="button" class="btn-remove-child" onclick="removeChild(this)">削除</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeChild(btn) {
            btn.closest('.child-item').remove();
        }
    </script>
</body>
</html>
@endsection
