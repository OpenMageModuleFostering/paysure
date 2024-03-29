<?php


class MageKenya_Paysuremobile_Model_Checkout extends Mage_Payment_Model_Method_Abstract {

    protected $_code          = 'paysuremobile';
    protected $_formBlockType = 'paysuremobile/form';
    protected $_infoBlockType = 'paysuremobile/info';
    protected $_order;
	
    
    const     WALLET_ID       = 'payment/paysuremobile/paysure_walletid';


    

    public function getCheckout() {
        return Mage::getSingleton('checkout/session');
    }

    public function getOrderPlaceRedirectUrl() {
        return Mage::getUrl('paysuremobile/redirect', array('_secure' => true));
    }

    public function getPaysuremobileUrl() {
		$url=$this->getPaysuremobileCheckoutFormFields();
	   $wsdl='https://epayments.paysure.co.ke/webdirect/onlinepay?wsdl';
		$client=new SoapClient($wsdl,array('trace'=>TRUE));
		$result=$client->pickData($url);
	   
	   $link="";
	   foreach($result as $key=>$value){
	   
		$link=$value;
	   
	   
	   
	   }
	   
	   return $link;
    }

    public function getLocale()
    {
        return Mage::app()->getLocale()->getLocaleCode();
    }
    
    public function getPaysuremobileCheckoutFormFields() {

        $order_id = $this->getCheckout()->getLastRealOrderId();
        $order    = Mage::getModel('sales/order')->loadByIncrementId($order_id);
        if ($order->getBillingAddress()->getEmail()) {
            $email = $order->getBillingAddress()->getEmail();
        } else {
            $email = $order->getCustomerEmail();
        }
		
			
		$i=1;
			
			for($i=1;$i<=1;$i++){
				
			
			$data.='<order>';
			$data.='<purchase>';
			$data.='<cardtype>3</cardtype>';
			$data.='<mname>'.Mage::getStoreConfig(MageKenya_Paysuremobile_Model_Checkout::WALLET_ID).'</mname>';
			//$data.='<mname>Paysure Limited</mname>';
            $data.='<refno>'.$order_id.'</refno>';
            $data.='<systemtraceno>'.(($order_id)/2).'</systemtraceno>';
            $data.='<surl>'.Mage::getUrl('paysuremobile/redirect/success', array('refno' => $order_id)).'</surl>';
            $data.='<furl>'.Mage::getUrl('paysuremobile/redirect/cancel', array('refno' => $order_id)).'</furl>';
            $data.='<description>'.Mage::helper('paysuremobile')->__('Payment for order #').$order_id.'</description>';
            $data.='<currency>'.$order->getOrderCurrencyCode().'</currency>';
            $data.='<buyer>'.$order->getBillingAddress()->getFirstname().'  '.$order->getBillingAddress()->getLastname().'</buyer>';
          	$data.='<amount>'.str_replace(".","",(round($order->getGrandTotal(),2))).'</amount>';			
            $data.='<email>'.$email.'</email>'; 
			$data.='</purchase>';
			$data.='</order>';
			
			}	
			
			
		$orderItems = $order->getAllItems();
		$dataitems = '<dataitems>';
		foreach ($orderItems as $item){
				$dataitems.= '<dataitem>';
				//$dataitems .='<itemnamcode>'.$item->getSku().'</itemnamcode>';
				$dataitems .= '<itemname>'.$item->getName() . '</itemname>';
				$dataitems .= '<itemprice>'.trim(round($item->getPrice(),2)) .'</itemprice>';
				$dataitems .= '<quantity>'.$item->getQty() .'</quantity>';				
				$dataitems .= '<subtotal>'.trim(round($item->getPrice(),2))*$item->getQty().'</subtotal>';
				$dataitems.= "</dataitem>";			
      
			}
		//$dataitems = '</dataitems>';
		$params=array('data'=>$data,'dataitems'=>$dataitems);
		return $params;
        
    }

    public function initialize($paymentAction, $stateObject)
    {
        $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT;
        $stateObject->setState($state);
        $stateObject->setStatus('pending_payment');
        $stateObject->setIsNotified(false);
    }

}
