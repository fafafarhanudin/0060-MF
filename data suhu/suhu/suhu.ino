#include <EEPROM.h>
#include <ESP8266WiFi.h>
#include <PubSubClient.h>
#include <Wire.h>
#include <stdlib.h>


WiFiClient espClient;
PubSubClient client(espClient);

//connect
const char* ssid = "fafa";
const char* password = "fafa23";
const char* mqtt_server = "riset.revolusi-it.com";

const char* topik = "iot/suhu";

String messages_terima;
String messages_kirim;


int led1=D1;
int led2=D2;
int led3=D3;
int led4=D4;


int sensor=A0;
float nilai_analog, nilai_volt, nilai_suhu; ////deklarasi




void konek_wifi()
{
WiFi.begin(ssid, password);
while (WiFi.status() != WL_CONNECTED) {
  delay(500);
  Serial.print(".");
}
Serial.println("");
Serial.println("WiFi connected"); // cetak wifi terkoneksi
}


void reconnect() {
// Ulang terus sampai terkoneksi
while (!client.connected()) {
Serial.print("Menghubungkan diri ke MQTT Server : "+(String)mqtt_server);
// Attempt to connect
if (client.connect("G.211.19.060")) { // gunakan NIM anda sebagai identitas client
Serial.println("connected");
// .subscribe ke topic
client.subscribe(topik);
} else {
  Serial.print("failed, rc=");
  Serial.print(client.state());
  Serial.println(" coba lagi dalam 5 detik...");
  // Wait 5 seconds before retrying
  delay(5000);
   }
  } 
}

float baca_suhu()
{


  nilai_analog=analogRead(sensor);
  //
  nilai_volt = (nilai_analog/1024)*3.3;
  //
  nilai_suhu = nilai_volt*100;

  Serial.println("Analog  ="+(String)nilai_analog);
  Serial.println("Voltase  ="+(String)nilai_volt);
  Serial.println("Suhu ="+(String)nilai_suhu+" Celcius");

  return nilai_suhu;
}


void callback(char* topic, byte* payload, unsigned int length) {

Serial.print("Pesan dari MQTT [");
Serial.print(topic);
Serial.print("] ");
messages_terima="";
for (int i=0;i<length;i++) { // susun jadi string saja...
  char receivedChar = (char)payload[i];
  messages_terima=messages_terima+(char)payload[i]; // ambil pesannya masukkan dalam string 
  }

  Serial.println(messages_terima);


//kirim
float suhu=baca_suhu();

messages_kirim=(String)suhu;
///
///
  char attributes[100];
messages_kirim.toCharArray(attributes, 100);
client.publish(topik,attributes,true);
Serial.println("messages : "+messages_kirim+"terkirim..");
delay(1000);


}

/////......begin...////
void setup()
{
  Serial.begin(9600);
  
  client.setServer(mqtt_server, 1883);
  client.setCallback(callback);
  pinMode(sensor, INPUT);
  pinMode(led1, OUTPUT);
  pinMode(led2, OUTPUT);
  pinMode(led3, OUTPUT);
  pinMode(led4, OUTPUT);
  digitalWrite(led1,0);
  digitalWrite(led2,0);
  digitalWrite(led3,0);
  digitalWrite(led4,0);

}

void loop()
{

  if(WiFi.status()!= WL_CONNECTED) { konek_wifi(); }///jika
  if (!client.connected()) { reconnect(); }//reco

  client.loop();
  
}

