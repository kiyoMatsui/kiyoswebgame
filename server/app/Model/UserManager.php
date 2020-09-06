<?php

declare(strict_types=1);

namespace App\Model;
use Nette\Security\Passwords;
use Nette\Mail\Message;
use Latte\Engine;
use Nette;

/**
 * Users management.
 */
final class UserManager implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	private const
	    TABLE_ACCOUNTS = 'accounts',
		TABLE_REGISTER = 'register',
		COLUMN_ID = 'accountid',
		COLUMN_NAME = 'username',
		COLUMN_EMAIL = 'email',
		COLUMN_PASSWORD_HASH = 'password_hash',
		ACTIVATION_CODE = 'activation_code';


	/** @var Nette\Database\Context */
	private $database;

	/** @var Passwords */
	private $passwords;

	/** @var Nette\Mail\Mailer */
	private $mailer;

	public function __construct(Nette\Database\Context $database, Nette\Security\Passwords $passwords,
	                            Nette\Mail\Mailer $mailer)
	{
		$this->database = $database;
		$this->passwords = $passwords;
		$this->mailer = $mailer;

	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials): Nette\Security\IIdentity
	{
		[$email, $password] = $credentials;

		$row = $this->database->table(self::TABLE_ACCOUNTS)
			->where(self::COLUMN_EMAIL, $email)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The e-mail is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif ($this->passwords->needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update([
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		return new Nette\Security\Identity($row[self::COLUMN_ID], $arr);
	}


	/**
	 * Adds new user.
	 * @throws DuplicateNameException
	 */
	public function add(string $username, string $email, string $password): void
	{

		Nette\Utils\Validators::assert($email, 'email');
		$row = $this->database->table(self::TABLE_REGISTER)
			->where(self::COLUMN_EMAIL, $email)
			->fetch();

		if ($row) {
			throw new DuplicateNameException('The e-mail already in use');
		}
		$activation_code = md5(uniqid(strval(rand()), true));
		$latte = new Engine;
		
		$activation_url = "https://" . $_SERVER['SERVER_NAME'] . "/messagereg/" . $activation_code ;
		$params = [
			'username' => $username,
			'activation_url' => $activation_url,
		];
		//var_dump("log test");
		$mail = new Message;
		$mail->setFrom('Admin <kiyo.matsui.mobile@gmail.com>')
		     ->addTo($email)
	         ->setSubject('Register Confirmation')
			 ->setHtmlBody(
				$latte->renderToString(__DIR__ . '/../Presenters/templates/components/registrationmail.latte', $params)
			);

		$this->mailer->send($mail);

		try {
			$this->database->table(self::TABLE_REGISTER)->insert([
				self::COLUMN_NAME => $username,
				self::COLUMN_EMAIL => $email,
				self::COLUMN_PASSWORD_HASH => $this->passwords->hash($password),
				self::ACTIVATION_CODE => $activation_code,
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new GenericSQLException;
		}
	}



	public function changePassword(string $password1, string $password2, string $password3): void
	{
		if ($password2 !== $password3) {
			throw new NameMatchException('New Passwords do not match');
		}

		$row = $this->database->table(self::TABLE_ACCOUNTS)
			->where(self::COLUMN_ID, $_SESSION['accountid'])
			->fetch();

		if (!$row) {
			throw new NoNameException('Cannot find user, something went wrong!');
		} else {

			if(!$this->passwords->verify($password1, $row[self::COLUMN_PASSWORD_HASH])) {
				throw new NameMatchException('Old password does not match');
			}
		}

		try {
			$this->database->query('UPDATE accounts SET password_hash = ? WHERE accountid = ?', 
			$this->passwords->hash($password2), $_SESSION['accountid']);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new GenericSQLException;
		}
	}


	public function changeEmail(string $email, string $password): void
	{
		$row = $this->database->table(self::TABLE_ACCOUNTS)
			->where(self::COLUMN_ID, $_SESSION['accountid'])
			->fetch();

		if (!$row) {
			throw new NoNameException('Cannot find user, something went wrong!');
		} else {

			if(!$this->passwords->verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
				throw new NoNameException('Password does not match');
			}
		}
		try {
			$this->database->query('UPDATE accounts SET email = ? WHERE accountid = ?', 
			$email, $_SESSION['accountid']);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new GenericSQLException;
		}
	}

	public function userRegistered(string $ac): void
	{	
		$row = $this->database->table(self::TABLE_REGISTER)
			->where(self::ACTIVATION_CODE, $ac)
			->fetch();

		if (!$row) {
			throw new NoNameException('Activation code not found, maybe you have activated already? Try Signing in.');
		} else  {
			$row2 = $this->database->table(self::TABLE_ACCOUNTS)
			    ->where(self::COLUMN_EMAIL, $row[self::COLUMN_EMAIL])
				->fetch();
			if ($row2) {
				throw new DuplicateNameException('This email is already registered.');
			} else {
			    try {
				    $this->database->table(self::TABLE_ACCOUNTS)->insert([
					    self::COLUMN_NAME => $row[self::COLUMN_NAME],
					    self::COLUMN_EMAIL => $row[self::COLUMN_EMAIL],
					    self::COLUMN_PASSWORD_HASH => $row[self::COLUMN_PASSWORD_HASH],
					]);
				} catch (Nette\Database\UniqueConstraintViolationException $e) {
				    throw new GenericSQLException;
				}
			}
		}
	}

	public function userResetPassword(string $email): void
	{
		$latte = new Engine;
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$newpassword = substr(str_shuffle($chars),0,6);
		$params = [
			'newpassword' => $newpassword,
		];

		$row = $this->database->table(self::TABLE_ACCOUNTS)
			->where(self::COLUMN_EMAIL, $email)
			->fetch();

		if (!$row) {
			throw new NoNameException('Email not found!');
		}

		$this->database->query('UPDATE accounts SET password_hash = ? WHERE accountid = ?', 
		$this->passwords->hash($newpassword), $row[self::COLUMN_ID]);

		$mail = new Message;
		$mail->setFrom('Admin <kiyo.matsui.mobile@gmail.com>')
		     ->addTo($email)
	         ->setSubject('Password reset')
			 ->setHtmlBody(
				$latte->renderToString(__DIR__ . '/../Presenters/templates/components/resetpasswordmail.latte', $params)
			);

		$this->mailer->send($mail);

	}

	public function changeUsername(string $username): void
	{

		$row = $this->database->table(self::TABLE_ACCOUNTS)
			->where(self::COLUMN_ID, $_SESSION['accountid'])
			->fetch();

		if (!$row) {
			throw new NoNameException('Cannot find user, something went wrong!');
		} 
		try {
			$this->database->query('UPDATE accounts SET username = ? WHERE accountid = ?', 
			$username, $_SESSION['accountid']);
			$_SESSION['username']= $username;
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new GenericSQLException;
		}
	}
}

class NoNameException extends \Exception
{ 
}

class DuplicateNameException extends \Exception
{ 
}

class GenericSQLException extends \Exception
{ 
}

class NameMatchException  extends \Exception
{ 
}
