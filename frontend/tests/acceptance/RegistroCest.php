<?php
namespace frontend\tests;
use frontend\tests\AcceptanceTester;
use yii\helpers\Url;


class RegistroCest
{
    /**
     * @var string
     */
    public $formularioId;

    public function _before(AcceptanceTester $I)
    {
        $this->formularioId = '#form-signup';
    }

    public function _after(AcceptanceTester $I)
    {
    }

    protected function formParams($login, $email, $password)
    {
        return [
            'SignupForm[username]' => $login,
            'SignupForm[email]' => $email,
            'SignupForm[password]' => $password,
        ];
    }

    // tests
    public function existePaginaLogin(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/signup'));
        $I->see('Signup');
        $I->seeInTitle('Signup');
        $I->wait(3);
    }

    public function checkEmpty(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/signup'));
        $I->submitForm($this->formularioId, $this->formParams(' ',' ',' '));
        $I->seeValidationError('Username cannot be blank.');
        $I->seeValidationError('Email cannot be blank.');
        $I->seeValidationError('Password cannot be blank.');
    }

    public function checkValidLogin(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/signup'));
        $I->submitForm($this->formularioId, $this->formParams('abelardo', 'abelardo@correo.com', 'password_0'));
        $I->see('Logout (abelardo)', 'form button[type=submit]');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }


}
