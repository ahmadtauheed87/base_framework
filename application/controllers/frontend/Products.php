<?php
class Products extends CI_Controller {

    
	function __construct() {
        parent::__construct();
        
        header('Content-Type', 'application/json');
        $is_logged_in = $this->session->userdata('useremail');
        if(!$is_logged_in)
        {
          redirect(base_url());
        }
        //session_start();
        //$this->load->library('session');
    }
      
    public function index() {

        $json = file_get_contents('https://fakestoreapi.com/products');
        //echo '<PRE>';print_r($json);die;
        $data['products'] = json_decode($json,TRUE);//echo '<PRE>';print_r($data['products']);die;
        $data['view'] = 'frontend/product_list';
        $this->load->view('frontend/layout/base_layout',$data);
    }

    public function product_detail($id) {
        $id = $this->uri->segment(4);
        $json = file_get_contents('https://fakestoreapi.com/products/'.$id);
        $data['product_details'] = json_decode($json,TRUE);//echo '<PRE>';print_r($data['product_details']);
        $data['view'] = 'frontend/product_details';
		    $this->load->view('frontend/layout/base_layout',$data);
    }
    
    public function update_cart_value() {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $price = $_POST['price'];
        
        //get data from cart
        $cart_details = $this->db->select('*')
                                ->where('cart_product_id',$id)
                                ->where('cart_created_by',$_SESSION['userid'])
                                ->where('cart_status','pending')
                                ->get('cart_details')->row_array();//echo $this->db->last_query();echo '<PRE>';print_r($cart_details);die;

        if(empty($cart_details)) {
            $insert_cartdata = array(
                'cart_product_id' => $id,
                'cart_product_quantity' => 1,
                'cart_product_title' => $title,
                'cart_product_rate' => $price,
                'cart_created_date' => date('Y-m-d H:i:s'),
                'cart_status' => 'pending',
                'cart_created_by' => $_SESSION['userid']
            );
            //insert cart data in DB
            $this->db->insert('cart_details',$insert_cartdata);
        } else {
            $new_quantity = $cart_details['cart_product_quantity'] + 1;
            $new_price = $cart_details['cart_product_rate'] + $price;
            //updating cart for same product
            $update_cart = array(
                'cart_product_quantity' => $new_quantity,
                'cart_product_rate' => $new_price,
            );
            $this->db->where('cart_product_id', $id)
                    ->where('cart_status','pending')
                    ->where('cart_created_by', $_SESSION['userid'])
                    ->update('cart_details',$update_cart);

        }

        $cart_value = $_SESSION['cart_value'] + 1;
        $newdata = array(
          'cart_value' => $cart_value     
        );  
        $this->session->set_userdata($newdata);

        echo $cart_value;
    }

    public function show_cart_details() {
        $data['cart_details'] = $this->db->select('*')
                                        ->where('cart_status','pending')
                                        ->where('cart_created_by',$_SESSION['userid'])
                                        ->get('cart_details')->result_array();//echo '<PRE>';print_r($data['cart_details']);die;
        
        $data['view'] = 'frontend/cart_details_view';
        $this->load->view('frontend/layout/base_layout',$data);
    }

    public function remove_cart_value() {
        $id = $_POST['id'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        $per_rate = $price / $quantity;
        $new_quantity = $quantity - 1;

        if($new_quantity != 0) {
            $update_array = array(
                'cart_product_quantity' => $new_quantity,
                'cart_product_rate' => $price - $per_rate
            );
            $this->db->where('cart_id', $id)
                    ->where('cart_status','pending')
                    ->where('cart_created_by', $_SESSION['userid'])
                    ->update('cart_details',$update_array);
        } elseif($new_quantity == 0) {
            $this->db->where('cart_id',$id)
                    ->delete('cart_details');
        }

        $cart_value = $_SESSION['cart_value'] - 1;
        $newdata = array(
          'cart_value' => $cart_value     
        );  
        $this->session->set_userdata($newdata);

        echo $cart_value;
    }

    public function checkout() {
      $data['cart_details'] = $this->db->select('*')
                                      ->where('cart_status','pending')
                                      ->where('cart_created_by',$_SESSION['userid'])
                                      ->get('cart_details')->result_array();

        $data['view'] = 'frontend/checkout_view';
        $this->load->view('frontend/layout/base_layout',$data);
    }

    public function thankyou() {

        $update_array = array(
            'cart_status' => 'completed'
        );
        $this->db->where('cart_status','pending')
                ->where('cart_created_by', $_SESSION['userid'])
                ->update('cart_details',$update_array);

        $newdata = array(
          'cart_value' => 0     
        );  
        $this->session->set_userdata($newdata);

        $data['view'] = 'frontend/thankyou_view';
        $this->load->view('frontend/layout/base_layout',$data);
    }
}