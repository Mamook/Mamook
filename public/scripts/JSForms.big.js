function email(obj, message){
	if(!/.+@.+\..+./.test(obj.value))
	{
		alert(message);
		return false;
	}
	return true;
};

function empty(obj, message){
	if(obj.value == '')
	{
		alert(message);
		return false;
	}
	return true;
};

function integer(obj, message){
	if(parseInt(obj.value) != obj.value)
	{
		alert(message);
		return false;
	}
	return true;
};

if(document.getElementById && document.createElement && document.getElementsByTagName)
{
	document.getElementsByTagName('form')[0].onsubmit = function(){
		return validate();
	};
}