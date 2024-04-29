<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>麻雀スコア管理</title>
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body onload="restoreData()">
<h1>麻雀スコア管理</h1>
<div id="tableContainer"></div>

<button onclick="addPlayer(); saveChanges()">プレイヤーを追加</button>
<button onclick="addRound(); saveChanges()">回数を追加</button>
<button onclick="saveChanges()">保存</button>

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
            html += `<th class="prayersCell"><input type="text" placeholder="プレイヤー${i + 1}"></th>`;
        }
        html += '</tr>';

        for (let i = 0; i < numRounds; i++) {
            html += `<tr id="round${i + 1}"><td>${i + 1}</td>`;
            for (let j = 0; j < numPlayers; j++) {
                html += `<td class="scoreCell"><input type="number" class="scoreInput" oninput="checkSum('round${i + 1}'); saveChanges()"></td>`;
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

    // 小計
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

    // 変更内容を保存
    function saveChanges() {
        let tableData = [];
        for (let i = 1; i <= numRounds; i++) {
            let rowData = [];
            for (let j = 1; j <= numPlayers; j++) {
                let cell = document.querySelector(`#round${i} .scoreCell:nth-child(${j + 1}) input`);
                rowData.push(cell.value);
            }
            tableData.push(rowData);
        }
        localStorage.setItem('scoreTableData', JSON.stringify(tableData));
    }

    // ページの読み込み時 保存データを復元
    function restoreData() {
        let savedData = localStorage.getItem('scoreTableData');
        if (savedData) {
            let tableData = JSON.parse(savedData);
            for (let i = 0; i < tableData.length; i++) {
                let rowData = tableData[i];
                for (let j = 0; j < rowData.length; j++) {
                    updateCell(`round${i + 1}`, j, rowData[j]);
                }
            }
        }
    }
</script>

</body>
</html>
