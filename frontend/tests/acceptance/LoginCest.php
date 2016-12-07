<?php
namespace frontend\tests;
use frontend\tests\AcceptanceTester;
use yii\helpers\Url;


class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage(Url::toRoute('/site/signup'));
    }

    public function _after(AcceptanceTester $I)
    {
    }

    protected function formParams($login, $password)
    {
        return [
            'LoginForm[username]' => $login,
            'LoginForm[password]' => $password,
        ];
    }

    // tests
    public function existePaginaLogin(AcceptanceTester $I)
    {
        $I->see('Signup');
        $I->seeInTitle('About');
        $I->click('About');
        $I->see('This is the About page.');
    }

    public function checkEmpty(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('', ''));
        $I->seeValidationError('Username cannot be blank.');
        $I->seeValidationError('Password cannot be blank.');
    }

    public function checkWrongPassword(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('admin', 'wrong'));
        $I->seeValidationError('Incorrect username or password.');
    }

    public function checkValidLogin(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('erau', 'password_0'));
        $I->see('Logout (erau)', 'form button[type=submit]');
        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }
}
