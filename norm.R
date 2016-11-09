args <- commandArgs(TRUE)
 
M <- as.integer(args[1])
N <- as.integer(args[2])
O <- as.integer(args[3])

x <- rnorm(M,N,O)

print(x)