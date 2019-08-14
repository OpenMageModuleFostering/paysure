<?php


class MageKenya_Paysuremobile_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $paysuremobile = Mage::getModel('paysuremobile/checkout');

        $form = new Varien_Data_Form();
        $form->setAction($paysuremobile->getPaysuremobileUrl())
            ->setId('pay')
            ->setName('pay')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($paysuremobile->getPaysuremobileCheckoutFormFields() as $field=>$value) {
//            echo $field.' - '.$value.'<br>';
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }

        $html = '<html><body>';
       // $html.= $this->__('Redirect to Paysure.co.ke ...');
        $html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.getElementById("pay").submit();</script>';
        $html.= '</body></html>';
        

        return $html;
    }
}
