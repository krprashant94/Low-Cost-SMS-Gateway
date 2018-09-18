#include <SoftwareSerial.h>
SoftwareSerial smsModule(9, 10); // RX, TX
//inpit pid:command
const int MAX = 50;
String state[MAX][2];
int cu_ptr = -1;

char form_computer;
String buff;
String form_sms;
String cmd = "";
int last_pos, tmp_pos;

int findProcess(String p_i_d){
  for(int i = 0; i < MAX; i++){
    if(state[i][0] == p_i_d){
      return i;
    }
  }
  return -1;
}
int find_text(String needle, String haystack) {
  int foundpos = -1;
  for (int i = 0; i <= haystack.length() - needle.length(); i++) {
    if (haystack.substring(i, needle.length()+i) == needle) {
      return i;
    }
  }
  return foundpos;
}

void exec(String cmd){
  //Serial.println("Input Command : "+cmd);
      if(cmd.substring(0, 2) == "GC"){
        cmd = cmd.substring(2, cmd.length());
        //Serial.println("Removing GC : "+cmd);
        if(cmd.substring(0, 4) == "+PHN"){
            smsModule.println("AT+CMGF=1");
            delay(200);
            cmd = cmd.substring(4, cmd.length());
            smsModule.println("AT+CMGS=\""+cmd+"\"");
            delay(200);
        }else if(cmd.substring(0, 4) == "+SID"){
            cu_ptr = (cu_ptr + 1) % MAX;
            cmd = cmd.substring(4, cmd.length());
            state[cu_ptr][0] = cmd;
            state[cu_ptr][1] = "PROCESSING";
              Serial.println("Process ID init to "+String(cmd));
        }else if(cmd.substring(0, 4) == "+GID"){
            cmd = cmd.substring(4, cmd.length());
            tmp_pos = findProcess(cmd);
            if(tmp_pos  != -1 && state[tmp_pos][1] != ""){
              Serial.println(state[tmp_pos][1]);
            }else{
              Serial.println("NOT_FOUND");
            }
        }else if(cmd.substring(0, 4) == "+LST"){
            Serial.println(state[cu_ptr][1]);
        }else if(cmd.substring(0, 4) == "+VER"){
            Serial.println("1.0");
        }else if(cmd.substring(0, 4) == "+APP"){
            Serial.println("Redmorus SMS Gateway");
        }else if(cmd.substring(0, 4) == "+HLT"){
            delay(1000);
        }else if(cmd.substring(0, 4) == "+FNT"){
            Serial.println(form_sms);
        }else if(cmd.substring(0, 4) == "+SND"){
            smsModule.println('');
            delay(100);
            smsModule.println("AT+CMGF=0");            
        }else if(cmd.substring(0, 4) == "+NXT"){
            cu_ptr = (cu_ptr + 1) % MAX;
        }
      }else{
          smsModule.println(cmd);
          delay(200);
      }
      //Serial.println("CMD : "+cmd);
}
void setup()
{
  Serial.begin(9600);
  smsModule.begin(9600);
  Serial.println("Redmorus SMS Gateway 1.0");
  Serial.println("------------------------------");
  Serial.println("GC+ Command list");
  Serial.println("PHN");
  Serial.println("MSG");
  Serial.println("SID");
  Serial.println("GID");
  Serial.println("SUB");
  Serial.println("VER");
  Serial.println("APP");
  Serial.println("LST");
  Serial.println("HLT");
  Serial.println("FNT");
  Serial.println("------------------------------");
}
void loop()
{
  if(Serial.available()){
    form_computer = Serial.read();
    if(form_computer == ':'){
      exec(buff);
      buff = "";
    }else{
      buff = buff + form_computer;
    }
    //Serial.println(buff);
    /*
     * 
     * 
      *
      /*
    if(form_computer == "msg"){
      mySerial.println("AT+CMGF=1");
      sms_ph = true;
      message_mode = true;
    }else if(message_mode){
      if(sms_ph){
        sms_ph = false;
        mySerial.println("AT+CMGS=\"" + form_computer+"\"");
      }else{
        message_mode = false;
        mySerial.println(form_computer+ '');
        mySerial.println("AT+CMGF=0");
      }
    }else if(form_computer == "last"){
      Serial.println(last_call_state);
    }else{
      mySerial.println(form_computer);
      if(handshke){
        last_call_state = "PROCESSING";
      }else{        
        last_call_state = "FAIL";
      }
    }
    */
  }else if(smsModule.available()){
    form_sms = smsModule.readString();
    form_sms.trim();
    if(find_text("OK", form_sms) != -1){
      state[cu_ptr][1] = "OK";
      Serial.println("CMD OK"+form_sms);
    }else{
      state[cu_ptr][1] = "FAIL";
      Serial.println("CMD FAIL"+form_sms);
    }
    //Serial.println(form_sms);
  }
  delay(20);
}
