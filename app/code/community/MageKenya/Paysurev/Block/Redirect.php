<?php


class MageKenya_Paysurev_Block_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $paysurev = Mage::getModel('paysurev/checkout');

        $form = new Varien_Data_Form();
        $form->setAction($paysurev->getPaysurevUrl())
            ->setId('pay')
            ->setName('pay')
            ->setMethod('POST')
            ->setUseContainer(true);
        foreach ($paysurev->getPaysurevCheckoutFormFields() as $field=>$value) {
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
