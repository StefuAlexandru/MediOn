<?php

class Client{
    private $firstName;
    private $lastName;
    private $password;
    private $email;
    private $phoneNumber;
    public $username;
  
    public function setFirstName($var){
        if (preg_match('~[0-9]+~', $var)) {
           return "First name can not contain numbers in it!";
        }
        $this->firstName = $var;
        return "";
    }
    public function setLastName($var){
       
        if (preg_match('~[0-9]+~', $var)) {
            return "Last name can not contain numbers in it!";
         }
        $this->lastName = $var;
        return "";
    }
    public function setPassword($var){
        $this->password = base64_encode($var);
   
    }
    public function setEmail($var) {
        if (!filter_var($var, FILTER_VALIDATE_EMAIL)) {
            return "Please enter a valid email!";
        }
        $this->email = $var;
        return "";
      }
      
    public function setPhoneNumber($var){
        
        if(!preg_match('/^[0-9]{10}+$/', $var)){
            return "Please enter a valid phone number";
        }
        $this->phoneNumber = $var;
        return "";
    }
    public function addClientInDatabase($con){
        $sql = "INSERT INTO  clients(firstName,lastName,userName,email,password,phoneNumber,admin,gaveLike,subscription,profilePicture) VALUES 
        ('{$this->firstName}' , '{$this->lastName}' ,'{$this->username}','{$this->email}' , '{$this->password}' ,'{$this->phoneNumber}',false,false , NULL,'None')";
        $query = mysqli_query($con,$sql) or die(mysqli_error($con));
        return $query;
    }
    
}

?>