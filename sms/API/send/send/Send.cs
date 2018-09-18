using System;
using System.Collections.Generic;
using System.IO.Ports;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace send
{
    class Send
    {
        static void Main(string[] args)
        {
            SerialPort port;
            try
            {
                String comPort = args[1];
                String cmd = args[2];
                if (args[0].Equals("to"))
                {
                    port = new SerialPort(comPort, 9600, Parity.None, 8, StopBits.One);
                    if (!port.IsOpen) { port.Open(); }
                    try
                    {
                        port.Write(cmd.ToCharArray(), 0, cmd.Length);
                        Console.Write("1 : Success");
                    }
                    catch (Exception e)
                    {
                        Console.Write("30 : " + e.Message);
                    }
                    
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
    }
}
