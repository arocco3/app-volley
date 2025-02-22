<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneo di Pallavolo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h1 class="text-center">🏐 Torneo di Pallavolo</h1>
        
        <!-- Sezione per visualizzare i risultati -->
        <h3 class="mt-4">📊 Risultati delle partite</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Squadra 1</th>
                    <th>Squadra 2</th>
                    <th>Punteggio</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody id="resultsTable">
                <tr><td colspan="4" class="text-center">Caricamento...</td></tr>
            </tbody>
        </table>

            <!-- Sezione per aggiungere un risultato -->
            <h3 class="mt-4">➕ Aggiungi un risultato</h3>
    <form id="addResultForm">
        <div class="mb-3">
            <label for="matchSelect" class="form-label">Seleziona una partita:</label>
            <select id="matchSelect" class="form-select" required>
                <option value="">Seleziona...</option>
            </select>
        </div>
        <div class="row">
            <div class="col">
                <label for="team1Score" class="form-label">Punteggio Squadra 1:</label>
                <input type="number" id="team1Score" class="form-control" min="0" required>
            </div>
            <div class="col">
                <label for="team2Score" class="form-label">Punteggio Squadra 2:</label>
                <input type="number" id="team2Score" class="form-control" min="0" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Salva Risultato</button>
    </form>

    <!-- Sezione per visualizzare le partite mancanti -->
    <h3 class="mt-4">⏳ Partite Mancanti</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Squadra 1</th>
                <th>Squadra 2</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody id="upcomingMatchesTable">
            <tr><td colspan="3" class="text-center">Caricamento...</td></tr>
        </tbody>
    </table>
</div>

<!-- JavaScript per caricare dati e interagire con le API -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        loadResults();
        loadUpcomingMatches();

        document.getElementById("addResultForm").addEventListener("submit", function(event) {
            event.preventDefault();
            submitResult();
        });
    });

    // Carica i risultati delle partite giocate
    function loadResults() {
        fetch("get_results.php")
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById("resultsTable");
                table.innerHTML = "";
                if (data.length === 0) {
                    table.innerHTML = "<tr><td colspan='4' class='text-center'>Nessuna partita giocata</td></tr>";
                } else {
                    data.forEach(match => {
                        table.innerHTML += `
                            <tr>
                                <td>${match.team1}</td>
                                <td>${match.team2}</td>
                                <td>${match.team1_score} - ${match.team2_score}</td>
                                <td>${match.match_date}</td>
                            </tr>
                        `;
                    });
                }
            })
            .catch(error => console.error("Errore nel caricamento dei risultati:", error));
    }

    // Carica le partite ancora da giocare
    function loadUpcomingMatches() {
        fetch("get_matches.php")
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById("upcomingMatchesTable");
                const select = document.getElementById("matchSelect");
                table.innerHTML = "";
                select.innerHTML = "<option value=''>Seleziona...</option>";

                if (data.length === 0) {
                    table.innerHTML = "<tr><td colspan='3' class='text-center'>Nessuna partita in programma</td></tr>";
                } else {
                    data.forEach(match => {
                        table.innerHTML += `
                            <tr>
                                <td>${match.team1}</td>
                                <td>${match.team2}</td>
                                <td>${match.match_date}</td>
                            </tr>
                        `;
                        select.innerHTML += `<option value="${match.id}">${match.team1} vs ${match.team2} (${match.match_date})</option>`;
                    });
                }
            })
            .catch(error => console.error("Errore nel caricamento delle partite:", error));
    }

    // Invia un nuovo risultato
    function submitResult() {
        const matchId = document.getElementById("matchSelect").value;
        const team1Score = document.getElementById("team1Score").value;
        const team2Score = document.getElementById("team2Score").value;

        if (!matchId || team1Score === "" || team2Score === "") {
            alert("Compila tutti i campi!");
            return;
        }

        fetch("update_score.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                match_id: matchId,
                team1_score: parseInt(team1Score),
                team2_score: parseInt(team2Score)
            })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.success || data.error);
            loadResults();
            loadUpcomingMatches();
        })
        .catch(error => console.error("Errore nell'invio del risultato:", error));
    }
</script>
</body>
</html>

