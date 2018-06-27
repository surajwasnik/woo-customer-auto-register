<?php 
    /**
     *
     * woo-customer-auto-register.
     *
     * @description: Create automatic user account for guest user after placing order.
     *
     * @since 1.0.0
     */
    

    function sunsid_woo_auto_register_guests( $order_id ) {
        if(!is_user_logged_in()){
            // get all the order data
            $order = new WC_Order($order_id);

            //get the user email from the order
            $order_email = $order->billing_email;
            $name = $order->billing_first_name.' '.$order->billing_last_name;

            // check if there are any users with the billing email as user or email
            $email = email_exists( $order_email );  
            $user = username_exists( $order_email );

            // if the UID is null, then it's a guest checkout
            if( $user == false && $email == false ){   
                // random password with 12 chars
                $random_password = wp_generate_password();

                // create new user with email as username & newly created pw
                $user_id = wp_create_user( $order_email, $random_password, $order_email );
                if ( !is_wp_error( $user_id ) ) { 
                    //Update user info
                   
                    wp_update_user(array( 'ID' => $user_id, 'user_nicename' => $name, 'display_name'=>$name ));
                     
                    //user's billing data
                    update_user_meta( $user_id, 'billing_address_1', $order->billing_address_1 );
                    update_user_meta( $user_id, 'billing_address_2', $order->billing_address_2 );
                    update_user_meta( $user_id, 'billing_city', $order->billing_city );
                    update_user_meta( $user_id, 'billing_company', $order->billing_company );
                    update_user_meta( $user_id, 'billing_country', $order->billing_country );
                    update_user_meta( $user_id, 'billing_email', $order->billing_email );
                    update_user_meta( $user_id, 'billing_first_name', $order->billing_first_name );
                    update_user_meta( $user_id, 'billing_last_name', $order->billing_last_name );
                    update_user_meta( $user_id, 'billing_phone', $order->billing_phone );
                    update_user_meta( $user_id, 'billing_postcode', $order->billing_postcode );
                    update_user_meta( $user_id, 'billing_state', $order->billing_state );
                     
                    // user's shipping data
                    update_user_meta( $user_id, 'shipping_address_1', $order->shipping_address_1 );
                    update_user_meta( $user_id, 'shipping_address_2', $order->shipping_address_2 );
                    update_user_meta( $user_id, 'shipping_city', $order->shipping_city );
                    update_user_meta( $user_id, 'shipping_company', $order->shipping_company );
                    update_user_meta( $user_id, 'shipping_country', $order->shipping_country );
                    update_user_meta( $user_id, 'shipping_first_name', $order->shipping_first_name );
                    update_user_meta( $user_id, 'shipping_last_name', $order->shipping_last_name );
                    update_user_meta( $user_id, 'shipping_method', $order->shipping_method );
                    update_user_meta( $user_id, 'shipping_postcode', $order->shipping_postcode );
                    update_user_meta( $user_id, 'shipping_state', $order->shipping_state );

                    // link past orders to this newly created customer
                    wc_update_new_customer_past_orders( $user_id );

                    //Send User password with welcome mail  
                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $headers[] = 'From: '.get_bloginfo( 'name' ).' <'.get_option('woocommerce_email_from_address').'>';          
                    $message = '<p>Hello <b>'.$name.'</b></p>';
                    $message .= '<p> Welcome to Amana, De Vereniging </p>';   
                    $message .= '<p> Please check your login details. </p>';                
                    $message .= '<p> <b>Username : </b> '.$order_email.' </p>';
                    $message .= '<p> <b>Password : </b> '.$random_password.' </p>';
                    wp_mail( $order_email, '['.get_bloginfo( 'name' ).'] Account Details', $message, $headers );
                    echo $message;            
                }
            }else{
                //Send User password with welcome mail  
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '.get_bloginfo( 'name' ).' <'.get_option('woocommerce_email_from_address').'>';          
                $message = '<p>Hello <b>'.$name.'</b></p>';
                $message .= '<p> You already have account with us, it looks like you forget your password, please click below link to reset your password and enjoy all services.</p>';   
                $message .= '<p> <a href="'.wp_lostpassword_url().'"> '.wp_lostpassword_url().' </a></p>';
                wp_mail( $order_email, '['.get_bloginfo( 'name' ).'] Account Notification', $message, $headers );
            }
        }
    }
     
    add_action( 'woocommerce_thankyou', 'sunsid_woo_auto_register_guests', 99, 1 );