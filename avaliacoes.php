<?php include 'header.php'; ?>

<section class="avaliacoes-container">
    <h2>Deixe sua Avaliação</h2>
    <form action="backend/processa_avaliacoes.php" method="POST" id="formAvaliacao">
        <label>1. Há quanto tempo é cliente da barbearia?</label>
        <div class="opcoes">
            <label><input type="radio" name="tempo_cliente" value="Menos de 1 ano" required> Menos de 1 ano</label>
            <label><input type="radio" name="tempo_cliente" value="Entre 1 e 3 anos"> Entre 1 e 3 anos</label>
            <label><input type="radio" name="tempo_cliente" value="Mais de 3 anos"> Mais de 3 anos</label>
        </div>

        <label>2. Qual idade da pessoa atendida?</label>
        <div class="opcoes">
            <label><input type="radio" name="idade_atendido" value="Entre 0 à 6 anos" required> Entre 0 à 6 anos</label>
            <label><input type="radio" name="idade_atendido" value="Entre 7 à 17 anos"> Entre 7 à 17 anos</label>
            <label><input type="radio" name="idade_atendido" value="Maior de 18 anos"> Maior de 18 anos</label>
            <label><input type="radio" name="idade_atendido" value="Maior de 30 anos"> Maior de 30 anos</label>
        </div>

        <label>3. Geralmente você faz quais serviços na Barbearia?</label>
        <div class="opcoes">
            <label><input type="radio" name="servico" value="Apenas Barba" required> Apenas Barba</label>
            <label><input type="radio" name="servico" value="Corte Tradicional"> Corte Tradicional</label>
            <label><input type="radio" name="servico" value="Degradê no Zero"> Degradê no Zero</label>
            <label><input type="radio" name="servico" value="Degradê Navalhado"> Degradê Navalhado</label>
            <label><input type="radio" name="servico" value="Tradicional e Barba"> Tradicional e Barba</label>
            <label><input type="radio" name="servico" value="Degradê no zero e barba"> Degradê no zero e barba</label>
            <label><input type="radio" name="servico" value="Degradê Navalhado e Barba"> Degradê Navalhado e Barba</label>
            <label><input type="radio" name="servico" value="Algum Corte + Barba + Sobrancelha"> Algum Corte + Barba + Sobrancelha</label>
        </div>

        <label>4. O que mais lhe faz sempre voltar à Barbearia?</label>
        <div class="opcoes">
            <label><input type="radio" name="motivo" value="O preço" required> O preço</label>
            <label><input type="radio" name="motivo" value="O ambiente"> O ambiente (Café, água, wi-fi, ar-condicionado)</label>
            <label><input type="radio" name="motivo" value="A qualidade do serviço"> A qualidade do serviço (Corte, Barba, sobrancelha)</label>
            <label><input type="radio" name="motivo" value="O atendimento"> O atendimento</label>
        </div>

        <button type="submit" id="botaoEnviar" disabled>Enviar Avaliação</button>
    </form>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("formAvaliacao");
        const botaoEnviar = document.getElementById("botaoEnviar");

        form.addEventListener("change", function () {
            const todasRespondidas = [...form.querySelectorAll("input[type=radio]")].every(input => 
                [...document.getElementsByName(input.name)].some(i => i.checked)
            );

            botaoEnviar.disabled = !todasRespondidas;
        });
    });
</script>

<?php include 'footer.php'; ?>
