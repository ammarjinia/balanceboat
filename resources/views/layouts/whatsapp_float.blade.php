<style>
    @media (max-width: 800px) {
        .kraya-floating-chat-icon {
            bottom: 50px;
            right: 15px;
        }
    }
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    (function() {
            window.chatWidgetConfig = {
                whatsappNumber: "917408846000",
                welcomeMessage: "Get the best wellness offers!",
                buttonText: "Offers",
                profileName: "Balanceboat",
                whatsAppUrl:"https://wa.me/917408846000?text=hi",
                profileImageUrl: "https://prod-kraya.s3.ap-south-1.amazonaws.com/9e59a78b-34ad-4668-ad90-191606b71938/B8OjqqkWZr6llRgO1Bw15Eu7K0NsTNDnLpoxHHy0.jpg",
                appUrl: "https://api.kraya-ai.com"
            };

            // Load the chat widget script
            var script = document.createElement('script');
            script.src = "https://api.kraya-ai.com/widget/chat.js?v=1745152261899";
            script.async = true;
            document.head.appendChild(script);
        }
    )();

});
$(window).ready(function(){
    setTimeout(function () {
    href = $(".kraya-chat-btn-link").attr("href");
    $(".kraya-chat-btn-link").attr("href", href+"?text=<?php echo url()->current();?>");
    },2000);
})
</script>