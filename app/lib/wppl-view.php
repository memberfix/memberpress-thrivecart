<?php

if(!class_exists('WPPL_View')){
    class WPPL_View{
        public function __construct($view){
            $this->add_styling();
            $this->add_notifications();

            require WPPL_PATH . "/app/views/$view.php";
        }

        /**
         * Adds the required CSS for the views
         *
         * @return void
         */

        private function add_styling(){
            ?>
                <style>
                    .wppl-message-container .wppl-notice{
                        position: relative;
                        padding: .75rem 1.25rem;
                        margin-bottom: 1rem;
                        border: 1px solid transparent;
                        border-radius: .25rem;
                        margin-right:1.25rem;
                        margin-top:1rem;
                    }

                    .wppl-message-container .wppl-alert{
                        background-color: #fff3cd;
                        color: #856404;
                        border-color: #ffeeba;
                    }

                    .wppl-message-container .wppl-success{
                        background-color:#d4edda;
                        color: #155724;
                        border-color:#c3e6cb;
                    }

                    .wppl-message-container .wppl-error{
                        background-color: #f8d7da;
                        color: #721c24;
                        border-color: #f5c6cb;
                    }

                    .wppl-input{
                        display:block;
                    }

                    .wppl-label{
                        display:block;
                    }

                    .wppl-submit{
                        margin-top:15px;
                    }
                </style>
            <?php
        }

        /**
         * Add the notifications partial
         *
         * @return void
         */

        private function add_notifications(){
            if(isset($_COOKIE['wppl_redirect_type']) && isset($_COOKIE['wppl_redirect_message'])){
                $type = $this->generate_message_class($_COOKIE['wppl_redirect_type']);
                $message = $_COOKIE['wppl_redirect_message'];

                $this->show_redirect_message($type, $message);
            }
        }

        /**
         * The notifications partial
         *
         * @param string $type
         * @param string $message
         * @return void
         */

        private function show_redirect_message(string $type, string $message){
            ?>
            <div class="wppl-message-container">
                <div class="wppl-notice <?php echo $type; ?>">
                    <p><?php echo $message; ?></p>
                </div>
            </div>

            <script>
                document.cookie = "wppl_redirect_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                document.cookie = "wppl_redirect_message=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            </script>
            <?php
        }

        /**
         * Generate which HTML class to assign
         *
         * @param string $type
         * @return void
         */

        private function generate_message_class(string $type){
            switch ($type){
                case 'success':
                    return 'wppl-success';
                    break;
                case 'alert':
                    return 'wppl-alert';
                    break;
                case 'error':
                    return 'wppl-error';
                    break;
            }
         }
    }
}