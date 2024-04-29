<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>麻雀スコア管理</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        input {
            width: 60px;
        }

        .error {
            background-color: red;
        }
    </style>
</head>
<body>
<h1>麻雀スコア管理</h1>
<div id="tableContainer"></div>

<button onclick="addPlayer()">プレイヤーを追加</button>
<button onclick="addRound()">回数を追加</button>

<script>
    let numPlayers = 4; // 初期のプレイヤー数
    let numRounds = 1; // 初期の回数
    let tableContainer = document.getElementById('tableContainer');
    let table = createTable();

    // テーブルを作成
    function createTable() {
        let html = '<table id="scoreTable">';
        html += '<tr><th>回数</th>';
        for (let i = 0; i < numPlayers; i++) {
            html += `<th>プレイヤー${i + 1}</th>`;
        }
        html += '</tr>';

        for (let i = 0; i < numRounds; i++) {
            html += `<tr id="round${i + 1}"><td>${i + 1}</td>`;
            for (let j = 0; j < numPlayers; j++) {
                html += `<td><input type="number" class="scoreInput" oninput="checkSum('round${i + 1}')"></td>`;
            }
            html += '</tr>';
        }
        html += '<tr id="subtotalRow"><td>小計</td>';
        for (let i = 0; i < numPlayers; i++) {
            html += `<td id="subtotal${i + 1}"></td>`;
        }
        html += '</tr>';
        html += '</table>';
        tableContainer.innerHTML = html;
        return document.getElementById('scoreTable');
    }

    // プレイヤーを追加
    function addPlayer() {
        if (numPlayers >= 8) {
            alert('プレイヤーは最大8人までです。');
            return;
        }
        numPlayers++;
        table = createTable();
    }

    // 回数を追加
    function addRound() {
        numRounds++;
        table = createTable();
    }

    // 合計をチェックしてアラート
    function checkSum(roundId) {
        let roundRow = document.getElementById(roundId);
        let inputs = roundRow.querySelectorAll('.scoreInput');
        let sum = Array.from(inputs).reduce((acc, input) => acc + parseInt(input.value || 0), 0);

        if (sum !== 0) {
            roundRow.classList.add('error');
        } else {
            roundRow.classList.remove('error');
        }

        calculateSubtotal();
    }

    // 小計を計算する関数
    function calculateSubtotal() {
        let subtotalRow = document.getElementById('subtotalRow');
        let inputs = document.querySelectorAll('.scoreInput');

        let subtotals = Array(numPlayers).fill(0);

        inputs.forEach((input, index) => {
            let playerIndex = index % numPlayers;
            subtotals[playerIndex] += parseInt(input.value || 0);
        });

        let html = '<td>小計</td>';
        for (let i = 0; i < numPlayers; i++) {
            html += `<td>${subtotals[i]}</td>`;
        }
        subtotalRow.innerHTML = html;
    }
</script>

</body>
</html>
