<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/contact_us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Paleomapa - Contacto</title>
</head>
<?php
  require_once "../components/header.php";
  require_once "../components/sidebar.php";
?>
<body>
    <div class="main-content">
        <h1 class="section-title">Contacto</h1>
            <div class="contact-container">
                <div class="contact-form">
                    <h2 class="section-title">Tem dúvidas? Deixe a sua Mensagem</h2>
                    <form id="contactForm" action="process_contact.php" method="POST">
                        <div class="form-group">
                            <label for="name">O Teu Nome:</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Assunto:</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">A tua Mensagem:</label>
                            <textarea id="message" name="message" class="form-control" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit">Enviar Mensagem</button>
                    </form>
                </div>
                
                <div class="contact-info">
                    <h2>Informação de Contacto</h2>
                    
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>R. Cmte. Pinho e Freitas 28, 3750-127 Águeda<br>Escola Superior de Tecnologia e Gestão de Águeda</span>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span>paleomapaestga@gmail.com</span>
                    </div>

                    <div class="map-container">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.4064575899856!2d-8.450192684595843!3d40.57232725373837!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd23a7f4cbb835c5%3A0xe8bedfed9d76b244!2sEscola%20Superior%20de%20Tecnologia%20e%20Gest%C3%A3o%20de%20%C3%81gueda!5e0!3m2!1spt-PT!2spt!4v1712419351056!5m2!1spt-PT!2spt" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
            
            <div class="faq-section">
                <h2 class="section-title">Perguntas Frequentes</h2>
                
                <div class="faq-item">
                    <div class="faq-question">O que é o Paleomapa?</div>
                    <div class="faq-answer">
                        O Paleomapa é uma plataforma interativa dedicada à visualização e gestão de dados paleontológicos e arqueológicos, permitindo explorar fósseis, sítios e pontos de interesse em mapas interativos com contextualização geográfica.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Quem pode utilizar o Paleomapa?</div>
                    <div class="faq-answer">
                        O Paleomapa pode ser utilizado por investigadores, professores, estudantes e entusiastas da paleontologia e arqueologia. A plataforma é pensada para fins educativos e científicos, com funcionalidades adaptadas a diferentes tipos de utilizadores.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Como posso contribuir com dados ou sugestões?</div>
                    <div class="faq-answer">
                        Aceitamos contribuições de investigadores, instituições e cidadãos interessados. É possível submeter novos registos de fósseis, sítios ou pontos de interesse através do nosso formulário de submissão. Todos os dados são revistos antes de serem publicados.
                    </div>
                </div>
                
                <div class="faq-item">
                    <div class="faq-question">Os dados apresentados são fiáveis?</div>
                    <div class="faq-answer">
                        Todos os dados no Paleomapa são validados por especialistas em paleontologia e arqueologia. A informação é atualizada regularmente com base em fontes científicas e contribuições credenciadas.
                    </div>
                </div>
            </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.faq-question').click(function() {
        $(this).toggleClass('active');
        $(this).next('.faq-answer').toggleClass('show');
    });
});
</script>
</html>