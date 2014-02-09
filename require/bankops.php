<?php
//file for all bank functions not playing a support/utility role or direct database access.
//This might be deleted as unneeded.

error_reporting(E_ALL); ///YES! YES! YES!
require_once 'dblib.php'; //database functions
require_once 'loglib.php'; //logging functions
require_once 'util.php'; //utility functions


function apply_tax($creditor, $list, $rate, $reason, $server)
//applies a tax against the list of users to be moved to the creditor at the rate given. 
//return 1 if failure in a transfer.
//Will apply tax to Inactive accounts. 
{
 //Apply taxes to all in the list
 foreach($list as &$taxee)
 {
  //get the money the Taxee has.
 	$currentfunds = get_funds(clean_input($taxee), $server);
	if ($currentfunds > 0) //make sure they got something to tax.
	{
 	 //We are gonna reuse clean_funds() to truncate the rate to the 2nd decimal place.
 	 //we then use it again to truncate the tax to be removed for consistancy.
 	 $tax = clean_funds($currentfunds*(clean_funds($rate)/100) );
 	 if(transfer_funds($creditor, $taxee, $creditor, $tax, $reason, $server))
	 {
	 	bank_error ('Could not complete taxation due to a transfer failer! Check previous messages.');
	 	bank_error ("Failure occured with attempt to tax: ".$taxee);
	 	return 1;
	 }
	}
 }
 mysql_query("COMMIT", $server);											 	
 return 0;
}

function transfer_funds($instigator, $sender, $receiver, $funds, $reason, $server)
//Takes money from one account and puts it in another. 
//A session MUST be active to use this function. ie begin_session() in util.php.
//Returns 0 on success. 
//Returns 1 on failure due to insufficient funds. 
//2 is returned if the account for the reciever did not exist.
//3 if the sender doesn't exist.
//4 if trying to send to yourself.
//5 if funds to move are neg
//6 if the receiver is an Inactive account.
//7 if the instigator is an Inactive account.
//8 if we couldn't actualy make all the changes. 
//Handles cleaning up all inputs.
//Handles error messages and logging.
//In terms of sql transactions, this function is not self contained. 
//ALWATS call begin_transaction() first before this function
//and use commit_transaction() or abort_transaction() to complete. 
{
 //clean inputs. 
 $funds = clean_funds($funds);	 //clean up the number.
 $reason = clean_input($reason); //clean up reason.
 $instigator = clean_input($instigator);
 $sender = clean_input($sender);
 $receiver = clean_input($receiver);
 
 if (($funds <= 0)||(!is_numeric($funds))) //make sure it's not a neg or 0 num, or has letters.
 {
 	bank_error ('Funds to be transfered must be a positive, non-negative number.<br />');
	return 5;
 }
 else //it's not negative or 0, and it's a number.
 {
 	if ($sender == $receiver) //Are they sending to themselves?
 	{
	bank_error ('You cannot transfer funds to yourself.<br />');	 
	 return 4;
 	}
 	else //They are not sending to themselves.
 	{
	 if (!find_account($sender, $server))//does the sender exist?
 	 {
 	 	bank_error ('Transfer failed due to no such sending user. This could indicate a bigger problem.');
	 	return 3;
	 }
	 else
	 {
	 	$currentfunds = get_funds($sender, $server); //We assume the sender exists since we just checked that.
	 }
	 //more checking
 	 if (!find_account($receiver, $server)) //does the reciever exist?
 	 {
 	 	bank_error ('Transfer failed due to no such recieving user. This could indicate a bigger problem.');
	 	return 2;
	 }
	 else if (get_status($receiver, $server) == 'Inactive')
	 {
	 	bank_error('Tranfer failed due to trying to send to Inactive account.');
		return 6;
	 }
	 else if (get_status($instigator, $server) == 'Inactive')
	 {
	 	bank_error('Tranfer failed due to trying use an Inactive account.');
		bank_error('Please speak with an Administrator.');
		return 7;
	 }
 	 else if ($funds > $currentfunds) //sender doesn't have the money to move. 
 	 {
 	 	bank_error ('Transfer failed due to insufficient funds.');
	 	return 1;
 	 }
 	 else //all is well. 
 	 {
	 	$bit = 0;
 	 	$bit = $bit + remove_funds($sender, $funds, $server);
 	 	$bit = $bit + add_funds($receiver, $funds, $server);
	 	//log the transfer
	 	$bit = $bit + (1 - log_transfer($instigator, $sender, $receiver, $funds, $reason, $server));
		
		if ($bit == 3) //check that it all worked. 
		{
		print ($bit);
		 return 8; //something failed
		}
		else //nothing failed. 
		{
		 print ("Transfer of ".$funds." from account ".$sender." to account ".$receiver." completed successfully.<br />");
		}
		return 0;
 	 }
	}
 }
}

?>
