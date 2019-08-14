<?php


class MageKenya_Paysurek_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $paysurek = Mage::getModel('paysurek/checkout');

        $form = new Varien_Data_Form();
        $form->setAction($paysurek->getPaysurekUrl())
            ->setId('pay')
            ->setName('pay')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($paysurek->getPaysurekCheckoutFormFields() as $field=>$value) {
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
