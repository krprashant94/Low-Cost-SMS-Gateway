using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.IO;
using System.IO.Ports;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace receive
{
    class Program
    {
        static string id;
        static void Main(string[] args)
        {
            SerialPort port;
            try
            {
                String comPort = args[1];
                String cmd = "GC+GID" + args[2]+":";
                id = args[2];
                if (args[0].Equals("from"))
                {
                    port = new SerialPort(comPort, 9600, Parity.None, 8, StopBits.One);
                    if (!port.IsOpen) { port.Open(); }
                    try
                    {
                        port.DataReceived += new SerialDataReceivedEventHandler(DataReceivedHandler);
                        port.Write(cmd.ToCharArray(), 0, cmd.Length);
                        Console.Read();
                    }
                    catch (Exception e)
                    {
                        Console.Write("44 : " + e.Message);
                    }
                    port.Close();
                }
                else
                {
                    Console.Write("57 : \'" + args[0] + "\' is an invalid API access token.");
                }
            }
            catch (Exception ex)
            {
                Console.Write("61 : " + ex.Message);
            }
        }
        private static void DataReceivedHandler(object sender, SerialDataReceivedEventArgs e)
        {
            SerialPort sp = (SerialPort)sender;
            string indata = sp.ReadLine().Trim();
            Console.Write(indata);
            //using (StreamWriter writetext = new StreamWriter("status.log"))
            //{
              //  writetext.WriteLine(indata);
            //}
            Environment.Exit(0);
        }
    }
}
