"use strict";

var requestURL = '';
 
var fetchRequest=function(requestURL){

  var constructed_url=requestURL;

  var headers=new Headers();

  var searchParams=new URLSearchParams();

  headers.append("X-Requested-With", "XMLHttpRequest");

//   headers.append("X-CSRF-TOKEN", document.querySelector('meta[name="csrf_token"]').content);

    
  var params={
      headers:headers,
      credentials: 'same-origin',
      cache: 'no-cache'
  };

  return {
      post:function(){
          params.method='POST';
          return fetch(constructed_url,params);
      },
        get:function(){
            params.method='GET';
            if(searchParams.toString()){
                constructed_url=constructed_url+'?'+searchParams.toString();
            }
            return fetch(constructed_url,params);
        },
      setBody:function(body){
          params.body=body;
      },
      setHeaders:function(key,value){
          headers.append(key,value);
      },

      setSearchParams:function(key,value){
        searchParams.set(key,value);
    },
  };
}